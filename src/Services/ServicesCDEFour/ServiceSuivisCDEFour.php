<?php
namespace Services\ServicesCDEFour;

use Exception;

use Controllers\Actions\ActionsSuiviDemandes;
use Controllers\ControllerMenu;

use Models\Entities\CDEFourH;
use Models\ModelCDEFourH;
use Models\ModelEtatModel;
use Models\ModelUser;

use Utils\Responses\HTMLResponse;
use Utils\Responses\Response;
use Utils\Responses\RouteRedirectionResponse;

use Utils\Session\DataHelper;
use Utils\Session\ErrorHelper;
use Utils\Session\UserHelper;


class ServiceSuivisCDEFour
{
	public function __construct() { }


	/**
	 * Summary of GetSuivisPage
	 * @return HTMLResponse
	 * @param int $demandId
	 */
	public function GetSuivisPage(CDEFourH $Demand): HTMLResponse {
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
				ErrorHelper::Set("cde_four", "La commande stock n'est pas précisée");
				return $OnError;
			
			case false:
				ErrorHelper::Set("cde_four", "Commande stock précisée est invalide");
				return $OnError;
		}

		//* get the demand is valid and verify if the user has access to it
		try
		{
			$Demand = (new ServiceCDEFourH)->Find($demandId);
		}
		catch (Exception $e)
		{
			ErrorHelper::Set("cde_four", "Impossible de récuperer la commande stock");
			ErrorHelper::SetDebug("cde_four", $e);

			return $OnError;
		}

		return $this->GetSuivisPage($Demand);
	}


	public function GetDetailPage(CDEFourH $Demand): HTMLResponse {
		$this->AssociateDetails($Demand);
		
		return new HTMLResponse(
			"public/Views/cde_fournisseur/cde_fournisseur_details.php",
			[
				'CDEFourH' => $Demand,
				'errors' => ErrorHelper::GetAll(),
			]
		);
	}


	public function GetDestinatairePage(CDEFourH $Demand): HTMLResponse {
		$this->AssociateDetails($Demand);

		try
		{
			$States = ModelEtatModel::GetInstance()->GetFournisseur();
			$ProductStates = ModelEtatModel::GetInstance()->GetLignes();
			$Destinataires = ModelUser::GetInstance()->GetAll();
		}
		
		catch (Exception $e)
		{
			ErrorHelper::SetDebug("select", $e);
			$States = $ProductStates = $Destinataires = null;
		}

		return new HTMLResponse(
			"public/Views/cde_fournisseur/cde_fournisseur_suivi_destinataire.php",
			[
				'CDEFourH' => $Demand,
				"States" => $States,
				"ProductStates" => $ProductStates,
				"Destinataires" => $Destinataires,
				'errors' => ErrorHelper::GetAll(),
			]
		);
	}


	public function GetDemandeurPage(CDEFourH $Demand): HTMLResponse {
		$this->AssociateDetails($Demand);

		return new HTMLResponse(
			"public/Views/cde_fournisseur/cde_fournisseur_suivi_demandeur.php",
			[
				'CDEFourH' => $Demand,
				'errors' => ErrorHelper::GetAll(),
			]
		);
	}


	private function AssociateDetails(CDEFourH $Demand): bool {
		try
		{
			$ServiceCDEFourH = new ServiceCDEFourH;
			$ServiceCDEFourH->AssociateAllReferences($Demand);

			$ServiceCDEFourH->AssociateProduits($Demand);
			
			$ServiceCDEClientL = new ServiceCDEFourL;
			foreach ($Demand->ListCDEFourL as $Product)
				$ServiceCDEClientL->AssociateAllReferences($Product);
		}
		catch (Exception $e)
		{
			ErrorHelper::Set('DetailsCDEFour', 'Une erreur est survuenue lors de la récupération des détails de la commande stock');
			return false;
		}

		return true;
	}
}