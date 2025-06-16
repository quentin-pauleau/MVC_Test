<?php
namespace Controllers;

use Enums\DemandeStates;
use Exception;

use Services\ServiceExportUploadedFiles;
use Services\ServicesDemandeDiverse\ServiceDemandeDiverse;
use Services\ServicesDegresUrgence\ServiceDegresUrgence;
use Services\ServicesDemandeDiverse\FormServiceDemandeDiverse;
use Services\ServicesUser\ServiceUser;
use Traits\Singleton;

use Utils\Database\Database;
use Utils\Database\DatabaseException;

use Controllers\Controller;
use Controllers\Actions\ActionsFormDemandeDiverse;

use Controllers\ControllerMenu;
use Controllers\Actions\ActionsMenu;

use Utils\FileHandler;
use Utils\Requests\Request;
use Utils\Responses\HTMLResponse;
use Utils\Responses\RedirectionResponse;
use Utils\Responses\Response;
use Utils\Responses\RouteRedirectionResponse;
use Utils\Session\DataHelper;
use Utils\Session\ErrorHelper;
use Utils\Session\UserHelper;

use Models\Entities\DemandeDiverse;

/**
 * Controller for "demandes diverses"
 * 
 * Possibles actions are listed in the {@see ActionsFormDemandeDiverse} enum class
 */
final class ControllerFormDemandeDiverse extends Controller
{
	use Singleton;

	private const TEMP_DIR = "temp/form_demande/";
	private const COLD_DIR = "C:/nchp/var/spool/ezged/instance/wait/xpert/xpert_demandes_diverses/";

	//! this is the path for the production server
	// private const COLD_DIR = "/app/spool/ocr/wait/web_form/demandes_diverses";


	public function Route($action): Response
	{
		//* check if logged user has the right to access the form
		if (!UserHelper::hasPermission("web_form_dem_diverses")) {
			return new RouteRedirectionResponse(ControllerMenu::class, ActionsMenu::MAIN);
		}

		//* select the right route
		switch ($action) {
			case ActionsFormDemandeDiverse::CANCEL_PROCESS:
				return self::CancelProcess();

			case ActionsFormDemandeDiverse::CONFIRM_PROCESS:
				return self::ConfirmProcess();

			//* Commande form
			case ActionsFormDemandeDiverse::DEMANDE_ADD:
				return self::DemandeAdd();

			case ActionsFormDemandeDiverse::DEMANDE_ADD_PROCESS:
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
		$DemandeDiverse= DataHelper::Get("Demande");

		if ($DemandeDiverse instanceof DemandeDiverse) {
			$files = FileHandler::GetFileEndingWith(self::TEMP_DIR, $DemandeDiverse->UUID);
			FileHandler::DeleteFiles($files);
		}

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
		$DemandeDiverse = DataHelper::Get("Demande");

		if (!($DemandeDiverse instanceof DemandeDiverse)) {
			ErrorHelper::Set("Demande", "Demande invalide");
			
			return new RouteRedirectionResponse(self::class, ActionsFormDemandeDiverse::DEMANDE_ADD);
		}

		$Database = new Database;

		// start a transaction
		// this enable rollback to cancel all inserts if any of them ended up failling
		$Database->BeginTransaction();

		$DemandeDiverse->id_etat = DemandeStates::DEFAULT;
		
		try
		{
			(new ServiceDemandeDiverse)->New($DemandeDiverse);
		}
		catch (Exception $e) 		{
			$Database->RollBackTransaction();
			ErrorHelper::Set("insert_query", $e->getMessage());
			
			return new RouteRedirectionResponse(self::class, ActionsFormDemandeDiverse::DEMANDE_ADD);
		}
		
		//* Move file to the definitive folder
		$ServiceFile = DataHelper::Get('ServiceExportUploadedFiles');

		if ($ServiceFile != null && $ServiceFile instanceof ServiceExportUploadedFiles) {
			if (!$ServiceFile->Commit(self::COLD_DIR, 'DEMANDES_UUID')) {
				//! on file commit failed
				$Database->RollBackTransaction();

				if (!$ServiceFile->Rollback(self::COLD_DIR)) {
					ErrorHelper::Set(
						"rollback_files", 
						"Le retour des fichiers joints a échoué",
						ErrorHelper::TYPE_DEBUG
					);
				}

				return new RouteRedirectionResponse(self::class, ActionsFormDemandeDiverse::DEMANDE_ADD);
			}
		}
		
		//* commit all change
		if (!$Database->CommitTransaction()) {
			//! on database commit failed
			//* retrieve the files back to the temporary directory
			if ($ServiceFile != null && $ServiceFile instanceof ServiceExportUploadedFiles) {
				if (!$ServiceFile->Rollback(self::COLD_DIR)) {
					ErrorHelper::Set(
						"rollback_files", 
						"Le retour des fichiers joints a échoué",
						ErrorHelper::TYPE_DEBUG
					);
				}
			}

			ErrorHelper::Set("insertion", "L'enregistrement de la demande a échoué");

			return new RouteRedirectionResponse(self::class, ActionsFormDemandeDiverse::DEMANDE_ADD);
		}

		//* delete the temporary data
		DataHelper::Clear();
		ErrorHelper::Clear();

		// Core::Redirect(ActionsMenu::MAIN, ControllerMenu::class);
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
		$Users = $Urgences = [];

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
			$Urgences = (new ServiceDegresUrgence)->FindAll();
		}
		catch (DatabaseException $e)
		{
			ErrorHelper::Set("urgence", $e);
		}

		$errors = ErrorHelper::GetAll();

		$DemandeDiverse = DataHelper::Get("Demande") ?? new DemandeDiverse();

		// require "public/Views/demande_diverses/demande_diverses_form.php";
		return new HTMLResponse(
			"public/Views/demande_diverses/demande_diverses_form.php",
			[
				"DemandeDiverse" => $DemandeDiverse,
				"Destinataires" => $Users,
				"Urgences" => $Urgences,
				"errors" => $errors,
			]
		);
	}


	public function DemandeAddProcess(): Response
	{
		ErrorHelper::Clear();

		$DemandeDiverse = new DemandeDiverse();

		$Request = Request::GetRequest();

		$DemandeDiverse->id_demandeur = UserHelper::GetUserId();

		if ($Request->Files->Get("join_images") != null) {
			$ServiceExportUploadedFiles = DataHelper::Get('ServiceExportUploadedFiles');
			
			if (!($ServiceExportUploadedFiles instanceof ServiceExportUploadedFiles)) {
				$ServiceExportUploadedFiles = new ServiceExportUploadedFiles(self::TEMP_DIR, $DemandeDiverse->UUID);
			}

			var_dump($Request->Files->Get("join_images"));
			
			DataHelper::Set('ServiceExportUploadedFiles', $ServiceExportUploadedFiles);

			if (!$ServiceExportUploadedFiles->SaveTemporaryNew($Request->Files->Get("join_images"))) {
				ErrorHelper::Set("demande_diverse", "Impossible de sauvegarder les fichiers");
				return new RouteRedirectionResponse(self::class, ActionsFormDemandeDiverse::DEMANDE_ADD);
			}
		}

		if (!(new FormServiceDemandeDiverse)->Handle($Request, $DemandeDiverse)) {
			DataHelper::Set("Demande", $DemandeDiverse);
			
			return new RouteRedirectionResponse(self::class, ActionsFormDemandeDiverse::DEMANDE_ADD);
		}

		if (!(new ServiceDemandeDiverse)->AssociateAllReferences($DemandeDiverse)) {
			DataHelper::Set("Demande", $DemandeDiverse);
			ErrorHelper::Set("demande_diverse", "Impossible de lier la demande à la référence");
			
			return new RouteRedirectionResponse(self::class, ActionsFormDemandeDiverse::DEMANDE_ADD);
		}

		DataHelper::Set("Demande", $DemandeDiverse);
		
		return new RouteRedirectionResponse(self::class, ActionsFormDemandeDiverse::CONFIRM_PROCESS);
	}

	#endregion Demande Actions 
}