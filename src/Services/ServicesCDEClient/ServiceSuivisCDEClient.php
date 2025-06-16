<?php
namespace Services\ServicesCDEClient;

use Exception;

use Controllers\ControllerMenu;

use Models\Entities\CDEClientH;
use Models\ModelCDEClientH;
use Models\ModelEtatModel;
use Models\ModelUser;

use Utils\Responses\HTMLResponse;
use Utils\Responses\Response;
use Utils\Responses\RouteRedirectionResponse;

use Utils\Session\ErrorHelper;
use Utils\Session\UserHelper;


class ServiceSuivisCDEClient
{
	public function __construct() { }

	/**
	 * Summary of GetSuivisPage
	 * @return HTMLResponse
	 * @param int $demandId
	 */
	public function GetSuivisPage(CDEClientH $Demand): HTMLResponse {
		$userId = UserHelper::GetUserId();

		if ($Demand->isClosed())
			return $this->GetDetailPage($Demand);

		if ($Demand->id_destinataire === $userId)
			return $this->GetDestinatairePage($Demand);

		if ($Demand->id_demandeur === $userId)
			return $this->GetDemandeurPage($Demand);

		return $this->GetDetailPage($Demand);
	}


	/**
	 * Summary of GetSuivisPage
	 * @return HTMLResponse|RouteRedirectionResponse|Response
	 * @param int $demandId
	 */
	public function GetSuivisPageById(?int $demandId, ?Response $OnError = null): Response {
		$OnError ??= new RouteRedirectionResponse(ControllerMenu::class);

		switch($demandId) {
			case null:
				ErrorHelper::Set("cde_client", "Demande client non précisée");
				return $OnError;		
			
			case false:
				ErrorHelper::Set("cde_client", "Commande/devis client précisée invalide");
				return $OnError;
		}

		//* get the demand is valid and verify if the user has access to it
		try
		{
			$Demand = (new ServiceCDEClientH)->Find($demandId);
		}
		catch (Exception $e)
		{
			ErrorHelper::SetDefault("cde_client", "Impossible de récuperer la demande client");
			ErrorHelper::SetDebug("cde_client", $e);

			return $OnError;
		}

		return $this->GetSuivisPage($Demand);
	}


	public function GetDetailPage(CDEClientH $Demand): HTMLResponse {
		$this->AssociateDetails($Demand);
		
		return new HTMLResponse(
			"public/Views/cde_client/cde_client_details.php",
			[
				'CDEClientH' => $Demand,
				'errors' => ErrorHelper::GetAll(),
			]
		);
	}


	public function GetDestinatairePage(CDEClientH $Demand): HTMLResponse {
		$this->AssociateDetails($Demand);

		try
		{
			$States = ModelEtatModel::GetInstance()->GetClient();
			$ProductStates = ModelEtatModel::GetInstance()->GetLignes();
			$Destinataires = ModelUser::GetInstance()->GetAll();
		}
		
		catch (Exception $e)
		{
			ErrorHelper::SetDebug("select", $e);
			$States = $ProductStates = $Destinataires = null;
		}

		return new HTMLResponse(
			"public/Views/cde_client/cde_client_suivi_destinataire.php",
			[
				'CDEClientH' => $Demand,
				"States" => $States,
				"ProductStates" => $ProductStates,
				"Destinataires" => $Destinataires,
				'errors' => ErrorHelper::GetAll(),
			]
		);
	}


	public function GetDemandeurPage(CDEClientH $Demand): HTMLResponse {
		$this->AssociateDetails($Demand);
		
		return new HTMLResponse(
			"public/Views/cde_client/cde_client_suivi_demandeur.php",
			[
				'CDEClientH' => $Demand,
				'errors' => ErrorHelper::GetAll(),
			]
		);
	}


	private function AssociateDetails(CDEClientH $Demand): bool {
		try
		{
			$ServiceCDEClientH = new ServiceCDEClientH;
			$ServiceCDEClientH->AssociateAllReferences($Demand);

			$ServiceCDEClientH->AssociateProduits($Demand);
			
			$ServiceCDEClientL = new ServiceCDEClientL;
			
			foreach ($Demand->Products as $Product)
				$ServiceCDEClientL->AssociateAllReferences($Product);
		}
		catch (Exception $e)
		{
			ErrorHelper::Set('DetailsCDEClient', 'Une erreur est survuenue lors de la récupération des détails de la demande client');
			return false;
		}

		return true;
	}
}