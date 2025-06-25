<?php
namespace Controllers;

use Exception;

use Enums\DemandeProductStates;
use Enums\DemandeStates;

use Services\ServiceExportUploadedFiles;
use Services\ServicesCDEFour\FormServiceCDEFourH;
use Services\ServicesCDEFour\FormServiceCDEFourL;
use Services\ServicesCDEFour\ServiceCDEFourH;
use Services\ServicesFournisseur\ServiceFournisseur;
use Services\ServicesUser\ServiceUser;
use Traits\Singleton;
use Core\Database\Database;
use Core\Database\DatabaseException;

use Controllers\Controller;
use Controllers\Actions\ActionsFormCDEFournisseur;
use Controllers\ControllerMenu;
use Controllers\Actions\ActionsMenu;

use Core\FileHandler;
use Core\Requests\Request;
use Core\Session\DataHelper;
use Core\Session\ErrorHelper;
use Core\Session\UserHelper;

use Core\Responses\RouteRedirectionResponse;
use Core\Responses\RedirectionResponse;
use Core\Responses\HTMLResponse;
use Core\Responses\Response;

use Models\Entities\CDEFourH;
use Models\Entities\CDEFourL;

use Models\ModelCDEFourH;
use Models\ModelCDEFourL;


/**
 * Controller for "Commandes fournisseur" (see {@see ControllerFormCDEClient} for "Commandes clients")
 * 
 * Possibles actions are listed in the {@see ActionsFormCDEFournisseur} class
 */
final class ControllerFormCDEFournisseur extends Controller
{
	use Singleton;

	private const TEMP_DIR = "temp/form_fournisseur/";
	private const COLD_DIR = "C:/nchp/var/spool/ezged/instance/wait/xpert/xpert_cde_fournisseur/";

	//! this is the path for the production server
	// private const COLD_DIR = "/app/spool/ocr/wait/web_form/cde_fournisseur";


	public function Route($action): Response
	{
		//* check if logged user has the right to access the form
		if (!UserHelper::hasPermission(UserHelper::PERMISSIONS_FORM_CDE_FOURNISSEUR)) {
			return new RouteRedirectionResponse(ControllerMenu::class, ActionsMenu::MAIN);
		}

		//* select the right route
		switch ($action) {
			case ActionsFormCDEFournisseur::CANCEL_PROCESS:
				return self::CancelProcess();
			
			case ActionsFormCDEFournisseur::DETAILS:
				return self::CDEFournisseurDetails();

			case ActionsFormCDEFournisseur::CONFIRM_PROCESS:
				return self::ConfirmProcess();

			//* Commande form
			case ActionsFormCDEFournisseur::CDEFourH_ADD:
				return self::CDEFourHAdd();

			case ActionsFormCDEFournisseur::CDEFourH_ADD_PROCESS:
				return self::CDEFourHAddProcess();

			case ActionsFormCDEFournisseur::CDEFourH_UPDATE:
				return self::CDEFourHUpdate();

			case ActionsFormCDEFournisseur::CDEFourH_UPDATE_PROCESS:
				return self::CDEFourHUpdateProcess();
			
			//* Commande Products form
			case ActionsFormCDEFournisseur::CDEFourL_ADD:
				return self::CDEFourLAdd();

			case ActionsFormCDEFournisseur::CDEFourL_ADD_PROCESS:
				return self::CDEFourLAddProcess();

			case ActionsFormCDEFournisseur::CDEFourL_UPDATE:
				return self::CDEFourLUpdate();

			case ActionsFormCDEFournisseur::CDEFourL_UPDATE_PROCESS:
				return self::CDEFourLUpdateProcess();

			case ActionsFormCDEFournisseur::CDEFourL_REMOVE_PROCESS:
				return self::CDEFourLRemoveProcess();

			//* File Join
			case ActionsFormCDEFournisseur::JOIN_FILE_ADD_PROCESS:
				return self::JoinFileAddProcess();

			
			default:
				return self::CDEFournisseurDetails();
		}
	}


	#region General Actions 

	/**
	 * Cancel the form and delete all temporary data
	 * @return RedirectionResponse
	 */
	public function CancelProcess(): Response
	{
		$CDEFourH = DataHelper::UnSet("TempCDEFourH");
		$CDEFourH = DataHelper::Get("CDEFourH");

		if ($CDEFourH instanceof CDEFourH) {
			$files = FileHandler::GetFileEndingWith(self::TEMP_DIR, $CDEFourH->UUID);
			FileHandler::DeleteFiles($files);
		}

		DataHelper::Clear();
		ErrorHelper::Clear();
		
		return new RouteRedirectionResponse(ControllerMenu::class, ActionsMenu::MAIN);
	}


	/**
	 * Display the details of the demande and the list of products
	 * @return HTMLResponse|RedirectionResponse
	 */
	public function CDEFournisseurDetails(): Response
	{
		//* verify if the CDE H is valid
		$CDEFourH = DataHelper::Get("CDEFourH");

		if (!($CDEFourH instanceof CDEFourH)) {
			ErrorHelper::Set("CDEFourH", "Aucune demande veuillez créer un nouvel cde H");
			return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::CDEFourH_ADD);
		}

		$files = FileHandler::GetFileEndingWith(self::TEMP_DIR, $CDEFourH->UUID, true);

		$errors = ErrorHelper::GetAll();
		
		ErrorHelper::Clear();

		return new HTMLResponse(
			"public/Views/cde_fournisseur/cde_fournisseur_form_details.php",
			[
				"CDEFourH" => $CDEFourH,
				"files" => $files,
				"errors" => $errors
			]
		);
	}


	/**
	 * Insert all the data and associate the files to the definitive folder
	 * @return RedirectionResponse
	 */
	public function ConfirmProcess(): Response 
	{
		ErrorHelper::Clear();
		
		//* verify if the demande is valid
		$CDEFourH = DataHelper::Get("CDEFourH");

		if (!($CDEFourH instanceof CDEFourH)) {
			ErrorHelper::SetDefault("CDEFourH", "Demande invalide");

			return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::DETAILS);
		}
		
		//* start a transaction
		$Database = new Database;
		$Database->BeginTransaction();


		//* Insert the demande
		try
		{
			(new ServiceCDEFourH)->New($CDEFourH);
		}
		catch (Exception $e)
		{
			$Database->RollBackTransaction();
			ErrorHelper::SetDefault("insert_query", $e);
			
			return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::DETAILS);
		}

		//* Move file to the definitive folder
		$ServiceFile = DataHelper::Get('ServiceExportUploadedFiles');

		if ($ServiceFile != null && $ServiceFile instanceof ServiceExportUploadedFiles) {
			if (!$ServiceFile->Commit(self::COLD_DIR, 'CDE_FOUR_H_UUID')) {
				$Database->RollBackTransaction();
				
				if (!$ServiceFile->Rollback(self::COLD_DIR))
					ErrorHelper::SetDebug(
						"rollback_files", 
						"Le retour des fichiers joints a échoué",
					);

				return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::DETAILS);
			}
		}
		
		// Commit all change
		if (!$Database->CommitTransaction()) {
			//* retrieve the files back to the temporary directory
			if ($ServiceFile != null && $ServiceFile instanceof ServiceExportUploadedFiles) {
				if (!$ServiceFile->Rollback(self::COLD_DIR)) {
					ErrorHelper::SetDebug(
						"rollback_files", 
						"Le retour des fichiers joints a échoué",
					);
				}
			}

			ErrorHelper::Set("insertion", "L'enregistrement de la demande et ces produits a échoué");
			
			return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::DETAILS);
		}

		DataHelper::Clear();
		ErrorHelper::Clear();

		return new RouteRedirectionResponse(ControllerMenu::class, ActionsMenu::MAIN);
	}

	#endregion



	#region CDEFourH Actions 

	/**
	 * Display the form to create a new CDE H
	 * @return HTMLResponse
	 */
	public function CDEFourHAdd(): Response
	{
		$Fournisseurs = $Users = [];

		try
		{
			$Fournisseurs = (new ServiceFournisseur)->FindAll();
		}
		catch (DatabaseException $e)
		{
			ErrorHelper::Set("fournisseurs", $e->getMessage());
		}

		try
		{
			$Users = (new ServiceUser)->FindAll();
		}
		catch (DatabaseException $e)
		{
			ErrorHelper::Set("users", $e->getMessage());
		}

		$errors = ErrorHelper::GetAll();

		$CDEFourH = DataHelper::Get("CDEFourH") ?? new CDEFourH();

		$action = ActionsFormCDEFournisseur::CDEFourH_ADD_PROCESS;

		return new HTMLResponse(
			"public/Views/cde_fournisseur/cde_fournisseur_form.php",
			[
				"CDEFourH" => $CDEFourH,
				"Fournisseurs" => $Fournisseurs,
				"Destinataires" => $Users,
				"errors" => $errors,
				"action" => $action
			]
		);
	}


	/**
	 * Process the form to create a new CDE H
	 * 
	 * {@see self::CDEFourHAdd} to display the form
	 * 
	 * {@see ServiceCDEFournisseur::HandleFormCDEFourH()} to handle the form
	 * 
	 * @return RedirectionResponse
	 */
	public function CDEFourHAddProcess(): Response
	{
		ErrorHelper::Clear();

		$CDEFourH = new CDEFourH;

		$CDEFourH->id_demandeur = UserHelper::GetUserId();
		
		DataHelper::Set("CDEFourH", $CDEFourH);

		if (!(new FormServiceCDEFourH)->Handle(Request::GetRequest(), $CDEFourH))
			return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::CDEFourH_ADD);

		if (!(new ServiceCDEFourH)->AssociateAllReferences($CDEFourH))
			return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::CDEFourH_ADD);
		
		return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::DETAILS);
	}


	/**
	 * Display the form to update a CDE H, already completed with data from the saved CDE H
	 * @return bool|RedirectionResponse
	 */
	public function CDEFourHUpdate(): Response
	{
		//* verify if the demand is valid
		$CDEFourH = DataHelper::Get("TempCDEFourH") ?? DataHelper::Get("CDEFourH") ?? new CDEFourH;

		if (!($CDEFourH instanceof CDEFourH)) {
			ErrorHelper::Set("CDEFourH", "Demande invalide veuillez créer un nouvelle commande stock");
			
			return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::CDEFourH_ADD);
		}

		$CDEFourH = clone $CDEFourH;

		//* get the data
		$Fournisseurs = $Users = [];

		try
		{
			$Fournisseurs = (new ServiceFournisseur)->FindAll();
		}
		catch (DatabaseException $e)
		{
			ErrorHelper::Set("fournisseur", $e->getMessage());
		}

		try
		{
			$Users = (new ServiceUser)->FindAll();
		}
		catch (DatabaseException $e)
		{
			ErrorHelper::Set("users", $e->getMessage());
		}

		$errors = ErrorHelper::GetAll();

		$action = ActionsFormCDEFournisseur::CDEFourH_UPDATE_PROCESS;

		return new HTMLResponse(
			'public/Views/cde_fournisseur/cde_fournisseur_form.php',
			[
				"CDEFourH" => $CDEFourH,
				"Fournisseurs" => $Fournisseurs,
				"Destinataires" => $Users,
				"errors" => $errors,
				"action" => $action
			]
		);
	}


	/**
	 * Process the form to update the CDE H
	 * @return RedirectionResponse
	 */
	public function CDEFourHUpdateProcess(): Response
	{
		ErrorHelper::Clear();

		$CDEFourH = DataHelper::Get("TempCDEFourH") ?? DataHelper::Get("CDEFourH") ?? new CDEFourH;

		if (!($CDEFourH instanceof CDEFourH)) {
			ErrorHelper::Set("CDEFourH", "Demande invalide veuillez créer un nouvelle commande stock");
			return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::CDEFourH_ADD);
		}

		$CDEFourH = clone $CDEFourH;

		try
		{
			if (!(new FormServiceCDEFourH)->Handle(Request::GetRequest(), $CDEFourH))
				throw new Exception;

			$CDEFourH->id_demandeur = UserHelper::GetUserId();

			if (!(new ServiceCDEFourH)->AssociateAllReferences($CDEFourH))
				throw new Exception;
		}
		catch (Exception $e)
		{
			DataHelper::Set("TempCDEFourH", $CDEFourH);
			return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::CDEFourH_UPDATE);
		}

		DataHelper::Set("CDEFourH", $CDEFourH);
		DataHelper::UnSet("TempCDEFourH");

		return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::DETAILS);
	}


	#endregion



	#region CDEFourL Actions 

	/**
	 * Display the form to add a new product to the saved CDE H
	 * @return HTMLResponse
	 */
	public function CDEFourLAdd(): Response
	{
		$errors = ErrorHelper::GetAll();

		$CDEFourL = DataHelper::Get("CDEFourL") ?? new CDEFourL;
		
		$action = ActionsFormCDEFournisseur::CDEFourL_ADD_PROCESS;

		// require "public/Views/cde_fournisseur/cde_fournisseur_form_produit.php";
		return new HTMLResponse(
			'public/Views/cde_fournisseur/cde_fournisseur_form_produit.php',
			[
				"CDEFourL" => $CDEFourL,
				"errors" => $errors,
				"action" => $action
			]
		);
	}


	/**
	 * Process the form to add a new product to the saved CDE H
	 * 
	 * {@see self::CDEFourLAdd} to display the form
	 * 
	 * {@see ServiceCDEFournisseur::HandleFormCDEFourL()} to handle the form
	 * 
	 * @return RedirectionResponse
	 */
	public function CDEFourLAddProcess(): Response {
		ErrorHelper::Clear();

		//* verify if the CDE is valid
		$CDEFourH = DataHelper::Get("CDEFourH");

		if (!($CDEFourH instanceof CDEFourH))
			return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::DETAILS);

		//* process the form
		$CDEFourL = new CDEFourL;

		if (!(new FormServiceCDEFourL())->Handle(Request::GetRequest(), $CDEFourL)) {
			DataHelper::Set("CDEFourL", $CDEFourL);
			return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::CDEFourL_ADD);
		}
		
		//* save the product
		$CDEFourH->ListCDEFourL->Add($CDEFourL);

		DataHelper::Set("CDEFourH", $CDEFourH);
		DataHelper::UnSet("CDEFourL");

		return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::DETAILS);
	}


	/**
	 * Display the form to update a product of the saved CDE H
	 * @return HTMLResponse|RedirectionResponse
	 */
	public function CDEFourLUpdate(): Response
	{
		ErrorHelper::Clear();

		//* verify if the CDE is valid
		$CDEFourH = DataHelper::Get("CDEFourH");

		if (!($CDEFourH instanceof CDEFourH))
			return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::DETAILS);

		//* verify if the product is valid
		$produit = $_GET["produit"] ?? DataHelper::Get("key") ?? null;

		if ($produit === null) {
			ErrorHelper::Set("produit", "Le produit à modifier n'est pas défini");
			return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::DETAILS);
		}

		$CDEFourL = $CDEFourH->ListCDEFourL->Get($produit);

		if ($CDEFourL === null) {
			ErrorHelper::Set("produit", "Le produit à modifier est invalide");
			return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::DETAILS);
		}

		//* display the form
		$errors = ErrorHelper::GetAll();

		DataHelper::Set("key", $produit);

		$action = ActionsFormCDEFournisseur::CDEFourL_UPDATE_PROCESS;

		return new HTMLResponse(
			'public/Views/cde_fournisseur/cde_fournisseur_form_produit.php',
			[
				"CDEFourL" => $CDEFourL,
				"errors" => $errors,
				"action" => $action
			]
		);
	}


	/**
	 * Process the form to update a product of the saved CDE H
	 * @return RedirectionResponse
	 */
	public function CDEFourLUpdateProcess(): Response
	{
		ErrorHelper::Clear();

		$key = DataHelper::Get("key");
		$CDEFourH = DataHelper::Get("CDEFourH");

		if (!($CDEFourH instanceof CDEFourH))
			return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::DETAILS);

		$CDEFourL = $CDEFourH->ListCDEFourL->Get($key);

		if ($CDEFourL === null) {
			ErrorHelper::Set("produit", "Le produit a été supprimé ou est invalide");
			return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::DETAILS);
		}

		if (!(new FormServiceCDEFourL)->Handle(Request::GetRequest(), $CDEFourL))
			return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::CDEFourL_UPDATE);

		DataHelper::Set("CDEFourH", $CDEFourH);
		DataHelper::UnSet("key");

		return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::DETAILS);
	}


	/**
	 * Provess the removing of a product from the saved CDE H
	 * @return RedirectionResponse
	 */
	public function CDEFourLRemoveProcess(): Response
	{
		ErrorHelper::Clear();

		//* verify if the CDE H is valid
		$CDEFourH = DataHelper::Get("CDEFourH");

		if (!($CDEFourH instanceof CDEFourH))
			return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::DETAILS);
		
		//* verify if the product is valid
		$Request = Request::GetRequest();
		
		$produit = null;

		switch ($produit = $Request->Query->FilterInt('produit')) {
			case 0:
				break; //! this makes it possible de delete the first value

			case null:
				ErrorHelper::Set("produit", "Le produit à modifié n'est pas défini");
				return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::DETAILS);

			case false:
				ErrorHelper::Set("produit", "Le produit à modifié est invalide");
				return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::DETAILS);
		}
		
		
		//* remove the product
		if (!$CDEFourH->ListCDEFourL->RemoveAt($produit)) {
			ErrorHelper::Set("removing", "Le produit n'a pas pu être supprimé");
			return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::DETAILS);
		}

		DataHelper::Set("CDEFourH", $CDEFourH);
		ErrorHelper::Clear();

		return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::DETAILS);
	}

	#endregion



	#region Join File Actions 

	/**
	 * Process to join the file into the temporary folder
	 * @return RedirectionResponse
	 */
	public function JoinFileAddProcess(): Response {
		ErrorHelper::Clear();
		
		$CDEFourH = DataHelper::Get("CDEFourH");

		if (!($CDEFourH instanceof CDEFourH))
			return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::DETAILS);

		if ($_FILES === []) {
			ErrorHelper::SetDefault(
				"join_file", 
				"Aucun fichier a joindre"
			);

			return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::DETAILS);
		}
		
		$Service = DataHelper::Get("ServiceExportUploadedFiles") ?? new ServiceExportUploadedFiles(self::TEMP_DIR, $CDEFourH->UUID);
		DataHelper::Set('ServiceExportUploadedFiles', $Service);

		if (!$Service->SaveTemporary('join_images')) {
			ErrorHelper::Set('save_temporary', 'Impossible de sauvegarder les pièces jointes dans le dossier temporaire');
			return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::DETAILS);
		}
		
		return new RouteRedirectionResponse(self::class, ActionsFormCDEFournisseur::DETAILS);
	}

	
	#endregion
}
