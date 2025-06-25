<?php
namespace Services\ServicesDemandeConges;

use Exception;

use Controllers\ControllerMenu;

use Models\Entities\DemandeConges;
use Models\Entities\User;
use Models\ModelDemandeConges;

use Services\ServicesDemandeConges\ServiceDemandeConges;

use Core\Responses\HTMLResponse;
use Core\Responses\Response;
use Core\Responses\RouteRedirectionResponse;

use Core\Session\ErrorHelper;
use Core\Session\UserHelper;

class ServiceSuivisDemandeConges
{
	public function __construct() {}

	/**
	 * Summary of GetSuivisPage
	 * @return HTMLResponse
	 * @param int $demandId
	 */
	public function GetSuivisPage(DemandeConges $Demand): HTMLResponse {
		$userId = UserHelper::GetUserId();

		if ($Demand->IsTreated())
			return $this->GetDetailPage($Demand);

		if (UserHelper::GetUser()->IsDirector() && !$Demand->IsTreatedBy($userId))
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
			$Demand = (new ServiceDemandeConges)->Find($demandId);
		}
		catch (Exception $e)
		{
			ErrorHelper::Set("demande_diverse", "Impossible de récuperer la demande");
			ErrorHelper::SetDebug("demande_diverse", $e);

			return $OnError;
		}

		return $this->GetSuivisPage($Demand);
	}


	public function GetDetailPage(DemandeConges $Demand): HTMLResponse {
		$this->AssociateDetails($Demand);

		return new HTMLResponse(
			"public/Views/demande_conges/demande_conges_details.php",
			[
				'DemandeConges' => $Demand,
				'errors' => ErrorHelper::GetAll(),
			]
		);
	}


	public function GetDestinatairePage(DemandeConges $Demand): HTMLResponse {
		$this->AssociateDetails($Demand);

		$numDirector = UserHelper::GetUserId() === User::DIRECTOR_1_ID 
			? 1
			: (
				UserHelper::GetUserId() === User::DIRECTOR_2_ID 
				? 2 
				: null
			);

		return new HTMLResponse(
			"public/Views/demande_conges/demande_conges_suivi_destinataire.php",
			[
				'DemandeConges' => $Demand,
				'errors' => ErrorHelper::GetAll(),
				'numDirector' => $numDirector,
			]
		);
	}


	public function GetDemandeurPage(DemandeConges $Demand): HTMLResponse {
		$this->AssociateDetails($Demand);

		return new HTMLResponse(
			"public/Views/demande_conges/demande_conges_suivi_demandeur.php",
			[
				'DemandeConges' => $Demand,
				'errors' => ErrorHelper::GetAll(),
			]
		);
	}


	private function AssociateDetails(DemandeConges $Demand): bool {
		try
		{
			$ServiceDemandeConges = new ServiceDemandeConges;
			$ServiceDemandeConges->AssociateAllReferences($Demand);
		}
		catch (Exception $e)
		{
			ErrorHelper::Set('DetailsCDEFour', 'Une erreur est survuenue lors de la récupération des détails de la commande stock');
			return false;
		}

		return true;
	}
}