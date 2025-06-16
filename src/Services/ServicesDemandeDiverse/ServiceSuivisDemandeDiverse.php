<?php
namespace Services\ServicesDemandeDiverse;

use Exception;

use Controllers\Actions\ActionsSuiviDemandes;
use Controllers\ControllerMenu;

use Models\Entities\DemandeDiverse;
use Models\ModelEtatModel;
use Models\ModelUser;

use Services\ServicesDemandeDiverse\ServiceDemandeDiverse;

use Utils\Responses\HTMLResponse;
use Utils\Responses\Response;
use Utils\Responses\RouteRedirectionResponse;

use Utils\Session\DataHelper;
use Utils\Session\ErrorHelper;
use Utils\Session\UserHelper;

class ServiceSuivisDemandeDiverse
{
	public function __construct() {}

	/**
	 * Summary of GetSuivisPage
	 * @return HTMLResponse
	 * @param int $demandId
	 */
	public function GetSuivisPage(DemandeDiverse $Demand): HTMLResponse {
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
				ErrorHelper::Set("demande_diverse", "La demande n'est pas précisée");
				return $OnError;
			
			case false:
				ErrorHelper::Set("demande_diverse", "Demande invalide");
				return $OnError;
		}

		//* get the demand is valid and verify if the user has access to it
		try
		{
			$Demand = (new ServiceDemandeDiverse)->Find($demandId);
		}
		catch (Exception $e)
		{
			ErrorHelper::Set("demande_diverse", "Impossible de récuperer la demande");
			ErrorHelper::SetDebug("demande_diverse", $e);

			return $OnError;
		}

		return $this->GetSuivisPage($Demand);
	}


	public function GetDetailPage(DemandeDiverse $Demand): HTMLResponse {
		$this->AssociateDetails($Demand);

		DataHelper::Set('sub_action', 'index.php?controller='.self::class.'&action='.ActionsSuiviDemandes::DEMANDEUR_DEMANDES_DIVERSES);

		return new HTMLResponse(
			"public/Views/demande_diverses/demande_diverse_suivi_demandeur.php",
			[
				'DemandeDiverse' => $Demand,
				'errors' => ErrorHelper::GetAll(),
			]
		);
	}


	public function GetDestinatairePage(DemandeDiverse $Demand): HTMLResponse {
		$this->AssociateDetails($Demand);

		try
		{
			$States = ModelEtatModel::GetInstance()->GetDemandeDiverse();
			$Destinataires = ModelUser::GetInstance()->GetAll();
		}
		catch (Exception $e)
		{
			ErrorHelper::SetDebug("select", $e);
			$States = $Destinataires = null;
		}

		return new HTMLResponse(
			"public/Views/demande_diverses/demande_diverse_suivi_destinataire.php",
			[
				'DemandeDiverse' => $Demand,
				"States" => $States,
				"Destinataires" => $Destinataires,
				'errors' => ErrorHelper::GetAll(),
			]
		);
	}


	public function GetDemandeurPage(DemandeDiverse $Demand): HTMLResponse {
		$this->AssociateDetails($Demand);

		DataHelper::Set('sub_action', 'index.php?controller='.self::class.'&action='.ActionsSuiviDemandes::DEMANDEUR_DEMANDES_DIVERSES);

		return new HTMLResponse(
			"public/Views/demande_diverses/demande_diverse_suivi_demandeur.php",
			[
				'DemandeDiverse' => $Demand,
				'errors' => ErrorHelper::GetAll(),
			]
		);
	}


	private function AssociateDetails(DemandeDiverse $Demand): bool {
		try
		{
			$ServiceDemandeDiverse = new ServiceDemandeDiverse;
			$ServiceDemandeDiverse->AssociateAllReferences($Demand);
		}
		catch (Exception $e)
		{
			ErrorHelper::Set('DetailsCDEFour', 'Une erreur est survuenue lors de la récupération des détails de la commande stock');
			return false;
		}

		return true;
	}
}