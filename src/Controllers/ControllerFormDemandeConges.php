<?php
namespace Controllers;

use Exception;

use Traits\Singleton;

use Models\ModelTypeConges;

use Services\ServicesDemandeConges\FormServiceDemandeConges;
use Services\ServicesDemandeConges\ServiceDemandeConges;

use Utils\Database\Database;
use Utils\Database\DatabaseException;

use Controllers\Controller;
use Controllers\Actions\ActionsFormDemandeConges;

use Controllers\ControllerMenu;
use Controllers\Actions\ActionsMenu;

use Utils\Requests\Request;

use Utils\Responses\HTMLResponse;
use Utils\Responses\RedirectionResponse;
use Utils\Responses\Response;
use Utils\Responses\RouteRedirectionResponse;

use Utils\Session\DataHelper;
use Utils\Session\ErrorHelper;
use Utils\Session\UserHelper;

use Models\Entities\DemandeConges;
use Models\ModelDemandeConges;


/**
 * Controller for "demandes diverses"
 * 
 * Possibles actions are listed in the {@see ActionsFormDemandeConges} enum class
 */
final class ControllerFormDemandeConges extends Controller
{
	use Singleton;

	private const TEMP_DIR = "temp/form_demande/";

	public function Route($action): Response
	{
		//* check if logged user has the right to access the form
		if (!UserHelper::hasPermission(UserHelper::PERMISSIONS_FORM_DEM_CONGES))
			return new RouteRedirectionResponse(ControllerMenu::class, ActionsMenu::MAIN);

		//* select the right route
		switch ($action) {
			case ActionsFormDemandeConges::CANCEL_PROCESS:
				return self::CancelProcess();

			case ActionsFormDemandeConges::CONFIRM_PROCESS:
				return self::ConfirmProcess();

			//* Commande form
			case ActionsFormDemandeConges::DEMANDE_ADD:
				return self::DemandeAdd();

			case ActionsFormDemandeConges::DEMANDE_ADD_PROCESS:
				return self::DemandeAddProcess();

			default:
				return self::DemandeAdd();
		}
	}


	#region General Actions 

	/**
	 * Cancel the form and delete temporary data and files
	 * @return RedirectionResponse
	 */
	public function CancelProcess(): Response
	{
		DataHelper::Clear();
		ErrorHelper::Clear();

		return new RouteRedirectionResponse(ControllerMenu::class, ActionsMenu::MAIN);
	}


	/**
	 * Insert the demande in the database and move the files to the GED
	 * @return RedirectionResponse
	 */
	public function ConfirmProcess(): Response 
	{
		ErrorHelper::Clear();
		
		//* verify is the demand is valid
		$DemandeConges = DataHelper::Get("Demande");

		if (!($DemandeConges instanceof DemandeConges)) {
			ErrorHelper::Set("Demande", "Demande invalide");
			return new RouteRedirectionResponse(self::class, ActionsFormDemandeConges::DEMANDE_ADD);
		}

		$Database = new Database;

		// start a transaction
		// this enable rollback to cancel all inserts if any of them ended up failling
		$Database->BeginTransaction();
		
		try
		{
			if (!ModelDemandeConges::GetInstance()->Insert($DemandeConges))
				throw new Exception("L'enregistrement de la demande a échoué");
		}
		catch (Exception $e) 		{
			$Database->RollBackTransaction();
			ErrorHelper::Set("insert_query", $e->getMessage());
			
			return new RouteRedirectionResponse(self::class, ActionsFormDemandeConges::DEMANDE_ADD);
		}
		
		//* commit all change
		if (!$Database->CommitTransaction()) {
			//! on database commit failed
			ErrorHelper::Set("insertion", "L'enregistrement de la demande a échoué");

			return new RouteRedirectionResponse(self::class, ActionsFormDemandeConges::DEMANDE_ADD);
		}

		//* delete the temporary data
		DataHelper::Clear();
		ErrorHelper::Clear();

		return new RouteRedirectionResponse(ControllerMenu::class, ActionsMenu::MAIN);
	}


	#endregion



	#region Demande Actions 

	/**
	 * Display the form to add a demande
	 * 
	 * {@see self::DemandeAddProcess()} for the process of the form
	 * 
	 * @return HTMLResponse
	 */
	public function DemandeAdd(): Response
	{
		try
		{
			$TypesConges = ModelTypeConges::GetInstance()->GetActives();
		}
		catch (DatabaseException $e)
		{
			ErrorHelper::Set("type_conges", $e->getMessage());
		}

		$errors = ErrorHelper::GetAll();

		$DemandeConges = DataHelper::Get("Demande") ?? new DemandeConges();

		return new HTMLResponse(
			"public/Views/demande_conges/demande_conges_form.php",
			[
				"DemandeConges" => $DemandeConges,
				"TypesConges" => $TypesConges,
				"errors" => $errors,
			]
		);
	}


	public function DemandeAddProcess(): Response
	{
		ErrorHelper::Clear();

		$DemandeConges = new DemandeConges();

		$Request = Request::GetRequest();

		$DemandeConges->id_demandeur = UserHelper::GetUserId();

		$Service = new ServiceDemandeConges;

		if (!(new FormServiceDemandeConges)->Handle($Request, $DemandeConges, )) {
			DataHelper::Set("Demande", $DemandeConges);
			return new RouteRedirectionResponse(self::class, ActionsFormDemandeConges::DEMANDE_ADD);
		}

		if (!$Service->AssociateAllReferences($DemandeConges)) {
			DataHelper::Set("Demande", $DemandeConges);
			ErrorHelper::Set("demande_diverse", "Impossible de lier la demande à la référence");
			
			return new RouteRedirectionResponse(self::class, ActionsFormDemandeConges::DEMANDE_ADD);
		}

		DataHelper::Set("Demande", $DemandeConges);

		return new RouteRedirectionResponse(self::class, ActionsFormDemandeConges::CONFIRM_PROCESS);
	}

	#endregion Demande Actions 
}