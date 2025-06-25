<?php
namespace Controllers;

use Exception;

use Enums\DemandeStates;
use Models\Entities\CDEClientH;
use Services\ServicesDegresUrgence\ServiceDegresUrgence;
use Services\ServicesFournisseur\ServiceFournisseur;
use Services\ServicesTypeLivraison\ServiceTypeLivraison;
use Services\ServicesUser\ServiceUser;
use Traits\Singleton;
use Core\Database\Database;
use Core\Database\DatabaseException;

use Controllers\Controller;
use Controllers\Actions\ActionsFormCDEClient;

use Controllers\ControllerMenu;
use Controllers\Actions\ActionsMenu;

use Core\FileHandler;
use Core\Requests\Request;
use Core\Responses\HTMLResponse;
use Core\Responses\RedirectionResponse;
use Core\Responses\Response;
use Core\Responses\RouteRedirectionResponse;
use Core\Session\DataHelper;
use Core\Session\ErrorHelper;
use Core\Session\UserHelper;

use Models\Entities\CDEClientH as Demand;
use Models\Entities\CDEClientL as Product;

use Services\ServicesCDEClient\FormServiceCDEClientH as FormDemand;
use Services\ServicesCDEClient\FormServiceCDEClientL as FormProduct;

use Services\ServicesCDEClient\ServiceCDEClientH as ServiceDemand;
use Services\ServicesCDEClient\ServiceCDEClientL as ServiceProduct;

use Services\ServiceExportUploadedFiles;


/**
 * Controller for "Commandes client" ({@see ControllerFormCDEFour} for "Commandes fournisseur")
 * 
 * {@see Services\ServicesCDEClient::ActionsFormCDEClient} availables actions
 * 
 * {@see Services\ServicesCDEClient::FormServiceCDEClientH} form handling for the demande
 * {@see Services\ServicesCDEClient::FormServiceCDEClientL} form handling for the products
 * 
 * {@see Services\ServicesCDEClient::ServicesCDEClientH} data handling for the demande
 * {@see Services\ServicesCDEClient::ServicesCDEClientL} data handling for the products
 */
final class ControllerFormCDEClient extends Controller
{
	use Singleton;

	private const TEMP_DIR = "temp/form_client/";

	private const COLD_DIR = "C:/nchp/var/spool/ezged/instance/wait/xpert/xpert_cde_client/";
	
	//! this is the path for the production server
	// private const COLD_DIR = "/app/spool/ocr/wait/web_form/cde_client";


	public function Route($action): Response
	{
		//* check if logged user has the right to access the form
		if (!UserHelper::hasPermission(UserHelper::PERMISSIONS_FORM_CDE_CLIENT))
			return new RouteRedirectionResponse(ControllerMenu::class, ActionsMenu::MAIN);

		
		//* select the right route
		switch ($action) {
			case ActionsFormCDEClient::CANCEL_PROCESS:
				return self::CancelProcess();
			
			case ActionsFormCDEClient::DETAILS:
				return self::CDEClientDetails();

			case ActionsFormCDEClient::CONFIRM_PROCESS:
				return self::ConfirmProcess();

			// Commande form
			case ActionsFormCDEClient::CDEClientH_ADD:
				return self::CDEClientHAdd();

			case ActionsFormCDEClient::CDEClientH_ADD_PROCESS:
				return self::CDEClientHAddProcess();

			case ActionsFormCDEClient::CDEClientH_UPDATE:
				return self::CDEClientHUpdate();

			case ActionsFormCDEClient::CDEClientH_UPDATE_PROCESS:
				return self::CDEClientHUpdateProcess();
			
			// Products form
			case ActionsFormCDEClient::CDEClientL_ADD:
				return self::CDEClientLAdd();

			case ActionsFormCDEClient::CDEClientL_ADD_PROCESS:
				return self::CDEClientLAddProcess();

			case ActionsFormCDEClient::CDEClientL_UPDATE:
				return self::CDEClientLUpdate();

			case ActionsFormCDEClient::CDEClientL_UPDATE_PROCESS:
				return self::CDEClientLUpdateProcess();

			case ActionsFormCDEClient::CDEClientL_REMOVE_PROCESS:
				return self::CDEClientLRemoveProcess();

			// Join File
			case ActionsFormCDEClient::JOIN_FILE_ADD_PROCESS:
				return self::JoinFileAddProcess();

			default:
				return self::CDEClientDetails();
		}
	}


	#region General Actions 

	/**
	 * Cancel the form and delete temporary data and files
	 * @return RedirectionResponse
	 */
	public function CancelProcess(): Response
	{
		$CDEClientH = DataHelper::Get("CDEClientH");

		//* delete the temporary files
		if ($CDEClientH != null && $CDEClientH instanceof CDEClientH) {
			$files = FileHandler::GetFileEndingWith(self::TEMP_DIR, $CDEClientH->UUID);
			FileHandler::DeleteFiles($files);
		}

		//* delete the temporary data
		DataHelper::Clear();
		ErrorHelper::Clear();

		return new RouteRedirectionResponse(ControllerMenu::class, ActionsMenu::MAIN);
	}


	public function CDEClientDetails(): Response
	{
		DataHelper::UnSet("TempCDEClientH"); // unset the updated cdeclient in case the update was cancel

		//* verify if the CDEClientH is valid
		$CDEClientH = DataHelper::Get("CDEClientH");

		if (!($CDEClientH instanceof CDEClientH)) {
			ErrorHelper::Set("CDEClientH", "Aucune demande veuillez créer un nouvel demande client");
			return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::CDEClientH_ADD);
		}

		//* get the additionnal data
		$files = FileHandler::GetFileEndingWith(self::TEMP_DIR, $CDEClientH->UUID, true);

		$errors = ErrorHelper::GetAll();
		ErrorHelper::Clear();
		
		return new HTMLResponse(
			"public/Views/cde_client/cde_client_form_details.php", 
			[
				"files" => $files,
				"CDEClientH" => $CDEClientH,
				"errors" => $errors,
			]
		);
	}


	public function ConfirmProcess(): Response 
	{
		ErrorHelper::Clear();
		
		//* verify is the demand is valid
		$CDEClientH = DataHelper::Get("CDEClientH");

		if (!($CDEClientH instanceof Demand)) {
			ErrorHelper::Set("CDEClientH", "Demande invalide");
			return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::DETAILS);
		}

		$Database = new Database;

		// start a transaction
		// this enable rollback to cancel all inserts if any of them ended up failling
		$Database->beginTransaction();

		try
		{
			$CDEClientH = (new ServiceDemand)->New($CDEClientH);
		}
		catch (Exception $e) 		{
			$Database->RollBackTransaction();
			ErrorHelper::Set("insert_query", $e);
			
			return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::DETAILS);
		}
		
		//* Move file to the definitive folder
		$ServiceFile = DataHelper::Get('ServiceExportUploadedFiles');

		if ($ServiceFile instanceof ServiceExportUploadedFiles) {
			if (!$ServiceFile->Commit(self::COLD_DIR, 'CDE_CLIENT_H_UUID')) {
				//! on file commit fail
				$Database->RollBackTransaction();
				
				//* retrieve the files back to the temporary directory
				if (!$ServiceFile->Rollback(self::COLD_DIR))
					ErrorHelper::Set(
						"rollback_files", 
						"Le retour des fichiers joints a échoué",
						ErrorHelper::TYPE_DEBUG
					);
				
				return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::DETAILS);
			}
		}
		

		//* Commit all change
		if (!$Database->CommitTransaction()) {
			//! on database commit fail
			//* retrieve the files back to the temporary directory
			if ($ServiceFile instanceof ServiceExportUploadedFiles) {
				if (!$ServiceFile->Rollback(self::COLD_DIR))
					ErrorHelper::Set(
						"rollback_files", 
						"Le retour des fichiers joints a échoué",
						ErrorHelper::TYPE_DEBUG
					);
			}

			ErrorHelper::Set("insertion", "L'enregistrement ". $CDEClientH->GetTypeSentence()." et ces produits a échoué");
			return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::DETAILS);
		}

		//* delete the temporary data
		DataHelper::Clear();
		ErrorHelper::Clear();

		return new RouteRedirectionResponse(ControllerMenu::class, ActionsMenu::MAIN);
	}


	#endregion



	#region CDEClientH Actions 

	/**
	 * Display the form to create a new CDEClientH
	 * @return HTMLResponse
	 */
	public function CDEClientHAdd(): Response
	{
		$TypesLivraison = $Users = $Urgences = [];

		try
		{
			$TypesLivraison = (new ServiceTypeLivraison)->FindAll();
		}
		catch (DatabaseException $e)
		{
			ErrorHelper::Set("type_livraison", $e);
		}

		try
		{
			$Users = (new ServiceUser)->FindAll();
		}
		catch (DatabaseException $e)
		{
			ErrorHelper::Set("users", $e);
		}

		try
		{
			$Urgences = (new ServiceDegresUrgence)->FindClient();
		}
		catch (DatabaseException $e)
		{
			ErrorHelper::Set("urgence", $e);
		}

		$errors = ErrorHelper::GetAll();

		$CDEClientH = DataHelper::Get("CDEClientH") ?? new Demand;

		$action = ActionsFormCDEClient::CDEClientH_ADD_PROCESS;

		return new HTMLResponse(
			"public/Views/cde_client/cde_client_form.php",
			[
				"action" => $action,
				"CDEClientH" => $CDEClientH,
				"TypesLivraison" => $TypesLivraison,
				"Destinataires" => $Users,
				"Urgences" => $Urgences,
				"errors" => $errors,
			]
		);
	}


	/**
	 * Process the form and save the demand
	 * 
	 * {@see self::CDEClientHAdd()} for the display of the form
	 * 
	 * {@see ServiceCDEClient::HandleFormCDEClientH()} for the form data handling
	 * 
	 * @return RedirectionResponse
	 */
	public function CDEClientHAddProcess(): Response
	{
		ErrorHelper::Clear();

		$CDEClientH = new Demand;

		if (!(new FormDemand)->Handle(Request::GetRequest(), $CDEClientH)) {
			//* failed to get data from the form
			DataHelper::Set("CDEClientH", $CDEClientH);
			return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::CDEClientH_ADD);
		}

		$CDEClientH->id_demandeur = UserHelper::GetUserId();

		if (!(new ServiceDemand)->AssociateAllReferences($CDEClientH)) {
			//* failed associate reference to other tables on the cde client
			DataHelper::Set("CDEClientH", $CDEClientH);
			return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::CDEClientH_ADD);
		}

		DataHelper::Set("CDEClientH", $CDEClientH);
		return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::DETAILS);
	}


	/**
	 * Display the form to update a saved demand
	 * 
	 * {@see self::CDEClientHUpdateProcess()} for the process of the form
	 * 
	 * @return HTMLResponse|RedirectionResponse
	 */
	public function CDEClientHUpdate(): Response
	{
		//* verify if the demand is valid
		$CDEClientH = DataHelper::Get("TempCDEClientH") ?? DataHelper::Get("CDEClientH") ?? new Demand;

		if (!($CDEClientH instanceof CDEClientH)) {
			ErrorHelper::Set("CDEClientH", "Demande invalide veuillez créer un nouvel commande/devis client");
			return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::CDEClientH_ADD);
		}

		//* get the data
		$TypesLivraison = $Users = $Urgences = [];

		try
		{
			$TypesLivraison = (new ServiceTypeLivraison)->FindAll();
		}
		catch (DatabaseException $e)
		{
			ErrorHelper::Set("type_livraison", $e);
		}

		try
		{
			$Users = (new ServiceUser)->FindAll();
		}
		catch (DatabaseException $e)
		{
			ErrorHelper::Set("users", $e);
		}

		try
		{
			$Urgences = (new ServiceDegresUrgence)->FindClient();
		}
		catch (DatabaseException $e)
		{
			ErrorHelper::Set("urgence", $e);
		}

		$errors = ErrorHelper::GetAll();

		$action = ActionsFormCDEClient::CDEClientH_UPDATE_PROCESS;

		return new HTMLResponse(
			"public/Views/cde_client/cde_client_form.php",
			[
				"action" => $action,
				"CDEClientH" => $CDEClientH,
				"TypesLivraison" => $TypesLivraison,
				"Destinataires" => $Users,
				"Urgences" => $Urgences,
				"errors" => $errors,
			]
		);
	}


	/**
	 * Process the form to update the saved demand
	 * 
	 * {@see self::CDEClientHUpdate()} for the display of the form
	 * 
	 * {@see ServiceCDEClient::HandleFormCDEClientH()} for the form data handling
	 * 
	 * @return RedirectionResponse
	 */
	public function CDEClientHUpdateProcess(): Response
	{
		ErrorHelper::Clear();

		$CDEClientH = DataHelper::Get("TempCDEClientH") ?? DataHelper::Get("CDEClientH") ?? new Demand;
		
		if (!($CDEClientH instanceof Demand)) {
			ErrorHelper::Set("CDEClientH", "Demande invalide veuillez créer un nouvel commande/devis client");
			return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::CDEClientH_ADD);
		}
		
		$Request = Request::GetRequest();

		try
		{
			if (!(new FormDemand)->Handle($Request, $CDEClientH, ))
				throw new Exception;

			$CDEClientH->id_demandeur = UserHelper::GetUserId();

			if (!(new ServiceDemand)->AssociateAllReferences($CDEClientH))
				throw new Exception;

		}
		catch (Exception $e)
		{
			DataHelper::Set("TempCDEClientH", $CDEClientH);
			return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::CDEClientH_UPDATE);
		}
		


		DataHelper::Set("CDEClientH", $CDEClientH);
		DataHelper::UnSet("TempCDEClientH");

		return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::DETAILS);
	}


	#endregion



	#region CDEClientL Actions 

	/**
	 * Display the form to add a product to the saved demand
	 * @return HTMLResponse|RedirectionResponse
	 */
	public function CDEClientLAdd(): Response
	{
		$errors = ErrorHelper::GetAll();
		ErrorHelper::Clear();

		//* verify if the demande is valid
		$CDEClientH = DataHelper::Get("CDEClientH");

		if (!($CDEClientH instanceof CDEClientH)) {
			ErrorHelper::Set("CDEClientH", "Demande invalide veuillez créer un nouvel commande/devis client");
			return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::CDEClientH_ADD);
		}

		//* get the data
		$Fournisseurs = [];

		try
		{
			$Fournisseurs = (new ServiceFournisseur)->FindAll();
		}
		catch (DatabaseException $e)
		{
			ErrorHelper::Set("fournisseur", $e);
		}

		$CDEClientL = DataHelper::Get("CDEClientL") ?? new Product;
		
		$action = ActionsFormCDEClient::CDEClientL_ADD_PROCESS;

		return new HTMLResponse(
			"public/Views/cde_client/cde_client_form_produit.php",
			[
				"action" => $action,
				"CDEClientL" => $CDEClientL,
				"Fournisseurs" => $Fournisseurs,
				"errors" => $errors,
			]
		);
	}


	/**
	 * Process the form to add a product to the saved demand
	 * 
	 * {@see self::CDEClientLAdd()} for the display of the form
	 * 
	 * {@see ServiceCDEClient::HandleFormCDEClientL()} for the form data handling
	 * 
	 * @return RedirectionResponse
	 */
	public function CDEClientLAddProcess(): Response {
		ErrorHelper::Clear();

		//* verify if the demand is valid
		$CDEClientH = DataHelper::Get("CDEClientH");

		if (!($CDEClientH instanceof Demand))
			return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::DETAILS);

		//* Handle the form
		$CDEClientL = new Product;

		$Request = Request::GetRequest();

		if (
			!(new FormProduct)->Handle($Request, $CDEClientL, )
			|| !(new ServiceProduct)->AssociateAllReferences($CDEClientL)
		) {
			DataHelper::Set("form", $CDEClientL);
			ErrorHelper::Set('CDEClientL', 'Une erreur est survenue lors de l\'ajout du produit');
			return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::CDEClientL_ADD);
		}


		//* Save the product in the demand
		$CDEClientL->CDEClientH = $CDEClientH;
		$CDEClientH->Products->Add($CDEClientL);

		DataHelper::Set("CDEClientH", $CDEClientH);
		DataHelper::UnSet("CDEClientL");

		return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::DETAILS);
	}


	/**
	 * Display the form to update a product of the saved demande
	 * @return HTMLResponse|RedirectionResponse
	 */
	public function CDEClientLUpdate(): Response
	{
		$errors = ErrorHelper::GetAll();
		ErrorHelper::Clear();

		//* verify if the demande is valid
		$CDEClientH = DataHelper::Get("CDEClientH");

		if (!($CDEClientH instanceof CDEClientH))
			return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::DETAILS);

		//* verify if the product is valid

		$Request = Request::GetRequest();

		$produit = null;

		switch ($produit = $Request->Query->FilterInt('produit') ?? DataHelper::Get("key")) {
			case 0: //! switch use "==" so 0 and null are "equals"
				break;

			case null:
				ErrorHelper::Set("produit", "Le produit à supprimer n'est pas défini");
				return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::DETAILS);
		}

		$CDEClientL = $CDEClientH->Products->Get($produit);

		if ($CDEClientL === null) {
			ErrorHelper::Set("produit", "Le produit à modifier est invalide");
			return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::DETAILS);
		}

		DataHelper::Set("key", $produit); // save the key for the update process

		//* get the data
		$Fournisseurs = [];

		try
		{
			$Fournisseurs = (new ServiceFournisseur)->FindAll();
		}
		catch (DatabaseException $e)
		{
			ErrorHelper::Set("fournisseur", $e);
			return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::DETAILS);
		}

		$errors = ErrorHelper::GetAll();

		$action = ActionsFormCDEClient::CDEClientL_UPDATE_PROCESS;

		return new HTMLResponse(
			"public/Views/cde_client/cde_client_form_produit.php",
			[
				"errors" => $errors,
				"action" => $action,
				"CDEClientL" => $CDEClientL,
				"Fournisseurs" => $Fournisseurs,
			]
		);
	}


	/**
	 * Process the form to update a product of the saved demande
	 * 
	 * {@see self::CDEClientLUpdate()} for the display of the form
	 * 
	 * {@see ServiceCDEClient::HandleFormCDEClientL()} for the form data handling
	 * 
	 * @return RouteRedirectionResponse
	 */
	public function CDEClientLUpdateProcess(): Response
	{
		ErrorHelper::Clear();

		//* verify if the update can be done
		$key = DataHelper::Get("key");
		$CDEClientH = DataHelper::Get("CDEClientH");

		if (!($CDEClientH instanceof Demand)) {
			return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::DETAILS);
		}

		$CDEClientL = $CDEClientH->Products->Get($key);

		if ($CDEClientL === null) {
			ErrorHelper::Set("produit", "Le produit n'existe plus");
			return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::DETAILS);
		}

		//* update the product
		
		if (
			!(new FormProduct)->Handle(Request::GetRequest(), $CDEClientL,)
			|| !(new ServiceProduct)->AssociateAllReferences($CDEClientL)
		) {
			// DataHelper::Set("CDEClientH", $CDEClientH);
			return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::CDEClientL_UPDATE);
		}

		//* save the updated product
		DataHelper::Set("CDEClientH", $CDEClientH);
		DataHelper::UnSet("key");

		return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::DETAILS);
	}


	/**
	 * Process the form to remove a product of the saved demande
	 * 
	 * {@see self::CDEClientLUpdate()} for the display of the form
	 * 
	 * @return RouteRedirectionResponse
	 */
	public function CDEClientLRemoveProcess(): Response
	{
		ErrorHelper::Clear();

		//* verify if the demande is valid
		$CDEClientH = DataHelper::Get("CDEClientH");

		if (!($CDEClientH instanceof Demand))
			return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::DETAILS);
		
		//* verify if the product is valid
		$Request = Request::GetRequest();
		
		$produit = null;

		switch ($produit = $Request->Query->FilterInt('produit')) {
			case null:
				if ($produit === 0)
					break; // switch use "==" and "0 == null => true", this makes it possible de delete the first value

				ErrorHelper::Set("produit", "Le produit à modifié n'est pas défini");
				return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::DETAILS);

			case false:
				ErrorHelper::Set("produit", "Le produit à modifié est invalide");
				return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::DETAILS);
		}
		
		//* remove the product from the demand
		if (!$CDEClientH->Products->RemoveAt($produit)) {
			ErrorHelper::Set("removing", "Le produit n'a pas pu être supprimé");
			return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::DETAILS);
		}
		
		DataHelper::Set("CDEClientH", $CDEClientH);

		ErrorHelper::Clear();

		return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::DETAILS);
	}

	#endregion



	#region Join File Actions 


	public function JoinFileAddProcess(): Response {
		ErrorHelper::Clear();
		
		//? verify if the demand is valid
		$CDEClientH = DataHelper::Get("CDEClientH");

		if (!($CDEClientH instanceof CDEClientH))
			return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::DETAILS);

		if ($_FILES === []) {
			ErrorHelper::Set(
				"join_file", 
				"Aucun fichier a joindre"
			);

			return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::DETAILS);
		}

		//* save files in the temporary directory
		$Service = DataHelper::Get("ServiceExportUploadedFiles") ?? new ServiceExportUploadedFiles(self::TEMP_DIR, $CDEClientH->UUID);
		DataHelper::Set('ServiceExportUploadedFiles', $Service);

		if (!$Service->SaveTemporary('join_images')) {
			//TODO: Add an error message
			return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::DETAILS);
		}
		
		return new RouteRedirectionResponse(self::class, ActionsFormCDEClient::DETAILS);
	}

	
	#endregion
}
