<?php
namespace Controllers;

use Exception;

use Controllers\Controller;
use Controllers\Actions\ActionsDebug;

use Models\Entities\User;
use Models\ModelDemandeConges;
use Services\ServiceDemandeDiverse;
use Services\ServicesCDEClient\ServiceCDEClientH;
use Services\ServicesCDEClient\ServiceSuivisCDEClient;
use Services\ServicesCDEFour\ServiceCDEFourH;
use Services\ServicesCDEFour\ServiceSuivisCDEFour;
use Services\ServicesDemandeConges\ServiceDemandeConges;
use Services\ServicesDemandeConges\ServiceDemandeCongesPDF;
use Services\ServicesDemandeConges\ServiceSuivisDemandeConges;
use Services\ServicesDemandeDiverse\ServiceSuivisDemandeDiverse;
use Traits\Singleton;

use Models\ModelCDEClientH;
use Models\ModelCDEFourH;
use Models\ModelDemandeDiverse;
use Core\Database\Database;
use Core\Requests\Request;
use Core\Responses\Response;
use Core\Responses\StringResponse;
use Core\Session\UserHelper;

class ControllerDebug extends Controller
{
	use Singleton;

	public function Route($action): Response
	{
		if (UserHelper::GetUserId() !== User::ADMIN_ID)
			throw new Exception('Vous n\'avez pas le droit d\'accés à cette page !');

		switch ($action) {
			//* Debug UUIDs
			case ActionsDebug::GENERATE_MISSING_UUID_CLIENT:
				return $this->GenerateMissingUUIDOnClient();

			case ActionsDebug::GENERATE_MISSING_UUID_FOUR:
				return $this->GenerateMissingUUIDOnFour();

			case ActionsDebug::GENERATE_MISSING_UUID_DEMANDES:
				return $this->GenerateMissingUUIDOnDemandes();


			case ActionsDebug::SET_CONVERSATION_THREAD_ID_FROM_0_TO_NULL:
				return $this->SetConversationThreadIdFrom0ToNULL();

			//* Debug PDFs
			case ActionsDebug::TEST_PDF_DEMAMNDE_CONGES:
				return $this->TestPDFDemamndeConges();

			//*Debug Suivis
			case ActionsDebug::TEST_SUIVI_CDE_CLIENT:
				return $this->TestSuivisCDEClient();
			
			case ActionsDebug::TEST_SUIVI_CDE_FOURNISSEUR:
				return $this->TestSuivisCDEFournisseur();
			
			case ActionsDebug::TEST_SUIVI_DEMANDE_DIVERSES:
				return $this->TestSuivisDemandeDiverse();
			
			case ActionsDebug::TEST_SUIVI_DEMANDE_CONGES:
				return $this->TestSuivisDemandeConges();

			default:
				throw new Exception("The controller 'debug' doesn't have a default route");
		}
	}


	/**
	 * Generate the missing UUID on CDE Client H
	 * @return StringResponse
	 */
	public function GenerateMissingUUIDOnClient(): Response {
		$ModelCDEClientH = ModelCDEClientH::GetInstance();
		try
		{
			$ListCDEClientH = $ModelCDEClientH->GetWithMissingUUID();

			if ($ListCDEClientH->IsEmpty())
				return new StringResponse("No CDE Client H with a missing UUID", false);

			$ModelCDEClientH->GenerateNewUUID($ListCDEClientH);
		}
		catch (Exception $e)
		{
			return new StringResponse("Error : \n$e", false);
		}

		return new StringResponse("Success");
	}


	/**
	 * Generate the missing UUID on CDE Four H
	 * @return StringResponse
	 */
	public function GenerateMissingUUIDOnFour(): Response {
		$ModelCDEFourH = ModelCDEFourH::GetInstance();
		try
		{
			$ListCDEFourH = $ModelCDEFourH->GetWithMissingUUID();

			if ($ListCDEFourH->IsEmpty())
				return new StringResponse("No CDE Four H with a missing UUID", false);

			$ModelCDEFourH->GenerateNewUUID($ListCDEFourH);
		}
		catch (Exception $e)
		{
			return new StringResponse("Error : $e", false);
		}

		return new StringResponse("Success", false);
	}


	/**
	 * Generate the missing UUID on Demandes Diverses
	 * @return StringResponse
	 */
	public function GenerateMissingUUIDOnDemandes(): Response {
		$ModelDemande = ModelDemandeDiverse::GetInstance();
		try
		{
			$ListDemande = $ModelDemande->GetWithMissingUUID();

			if ($ListDemande->IsEmpty())
				return new StringResponse("No demande with a missing UUID", false);

			$ModelDemande->GenerateNewUUID($ListDemande);
		}
		catch (Exception $e)
		{
			return new StringResponse("Error : $e", false);
		}

		return new StringResponse("Success", false);
	}


	public function SetConversationThreadIdFrom0ToNULL(): Response {
		try
		{
			$Database = new Database;

			$result = $Database->Update(
				'UPDATE cdeclient_h SET CDECLIENT_H_IDCONVERSATIONFIL = NULL WHERE CDECLIENT_H_IDCONVERSATIONFIL = 0;
				UPDATE cdefour_h SET CDEFOUR_H_IDCONVERSATIONFIL = NULL WHERE CDEFOUR_H_IDCONVERSATIONFIL = 0;
				UPDATE DEMANDES SET DEMANDES_IDCONVERSATIONFIL = NULL WHERE DEMANDES_IDCONVERSATIONFIL = 0;'
			);

			if (!$result)
				return new StringResponse("No demande with a missing UUID", false);
		}
		catch (Exception $e)
		{
			return new StringResponse("Error : $e", false);
		}

		return new StringResponse("Success", false);
	}

	
	public function TestPDFDemamndeConges(): Response {
		$Request = Request::GetRequest();

		$id = null;

		try
		{
			switch ($id = $Request->Query->FilterInt('id')) {
				case null:
					throw new Exception('Parameter "id" of type int required');

				case false:
					throw new Exception('Parameter "id" must be of type int');
			}

			$DemandeConges = ModelDemandeConges::GetInstance()->GetById($id);

			if ($DemandeConges === null)
				throw new Exception("Demande de conges not found with id = \"$id\"");

			(new ServiceDemandeConges)->AssociateAllReferences($DemandeConges);

			$ServicePDF = new ServiceDemandeCongesPDF;
			$ServicePDF->CreatePDF($DemandeConges);
			$ServicePDF->Export();

		}
		catch (Exception $e)
		{
			return new StringResponse("Error : $e", false);
		}

		return new StringResponse("Success");
	}
	
	
	#region Debug Suivis
	public function TestSuivisCDEClient(): Response {
		$Request = Request::GetRequest();

		$id = $Request->Query->FilterInt('id');
		$type = $Request->Query->FilterInt('type') ?? $Request->Query->FilterString('type');

		if ($id === null || $type === null)
			return new StringResponse("Parameters 'id' and 'type' are required", false);

		$CDEClientH = (new ServiceCDEClientH)->Find($id);

		switch ($type) {
			case 0: case 'default':
				return (new ServiceSuivisCDEClient)->GetSuivisPage($CDEClientH);
			
			case 1: case 'detail':
				return (new ServiceSuivisCDEClient)->GetDetailPage($CDEClientH);
			
			case 2: case 'demandeur':
				return (new ServiceSuivisCDEClient)->GetDemandeurPage($CDEClientH);
			
			case 3: case 'destinataire':
				return (new ServiceSuivisCDEClient)->GetDestinatairePage($CDEClientH);
		}

		return new StringResponse('Type must be 1 for detail, 2 for demandeur or 3 for destinataire', false);
	}


	public function TestSuivisCDEFournisseur(): Response {
		$Request = Request::GetRequest();

		$id = $Request->Query->FilterInt('id');
		$type = $Request->Query->FilterInt('type') ?? $Request->Query->FilterString('type');

		if ($id === null || $type === null)
			return new StringResponse("Parameters 'id' and 'type' are required", false);

		$CDEFourH = (new ServiceCDEFourH)->Find($id);

		switch ($type) {
			case 0: case 'default':
				return (new ServiceSuivisCDEFour)->GetSuivisPage($CDEFourH);
			
			case 1: case 'detail':
				return (new ServiceSuivisCDEFour)->GetDetailPage($CDEFourH);
			
			case 2: case 'demandeur':
				return (new ServiceSuivisCDEFour)->GetDemandeurPage($CDEFourH);
			
			case 3: case 'destinataire':
				return (new ServiceSuivisCDEFour)->GetDestinatairePage($CDEFourH);
		}

		return new StringResponse('Type must be 1 for "detail", 2 for "demandeur" or 3 for "destinataire"', false);
	}


	public function TestSuivisDemandeDiverse(): Response {
		$Request = Request::GetRequest();

		$id = $Request->Query->FilterInt('id');
		$type = $Request->Query->FilterInt('type') ?? $Request->Query->FilterString('type');

		if ($id === null || $type === null)
			return new StringResponse("Parameters 'id' and 'type' are required", false);

		$DemandeDiverse = (new ServiceDemandeDiverse)->Find($id);

		switch ($type) {
			case 0: case 'default':
				return (new ServiceSuivisDemandeDiverse)->GetSuivisPage($DemandeDiverse);
			
			case 1: case 'detail':
				return (new ServiceSuivisDemandeDiverse)->GetDetailPage($DemandeDiverse);
			
			case 2: case 'demandeur':
				return (new ServiceSuivisDemandeDiverse)->GetDemandeurPage($DemandeDiverse);
			
			case 3: case 'destinataire':
				return (new ServiceSuivisDemandeDiverse)->GetDestinatairePage($DemandeDiverse);
		}

		return new StringResponse('Type must be 1 for detail, 2 for demandeur or 3 for destinataire', false);
	}


	public function TestSuivisDemandeConges(): Response {
		$Request = Request::GetRequest();

		$id = $Request->Query->FilterInt('id');
		$type = $Request->Query->FilterInt('type') ?? $Request->Query->FilterString('type');

		if ($id === null || $type === null)
			return new StringResponse("Parameters 'id' and 'type' are required", false);

		$DemandeConges = (new ServiceDemandeConges)->Find($id);

		switch ($type) {
			case 0: case 'default':
				return (new ServiceSuivisDemandeConges)->GetSuivisPage($DemandeConges);
			
			case 1: case 'detail':
				return (new ServiceSuivisDemandeConges)->GetDetailPage($DemandeConges);
			
			case 2: case 'demandeur':
				return (new ServiceSuivisDemandeConges)->GetDemandeurPage($DemandeConges);
			
			case 3: case 'destinataire':
				return (new ServiceSuivisDemandeConges)->GetDestinatairePage($DemandeConges);
		}

		return new StringResponse('Type must be 1 for detail, 2 for demandeur or 3 for destinataire', false);
	}

	#endregion Debug Suivis
}