<?php
namespace Controllers;

use Controllers\Actions\ActionsMenu;
use Enums\DemandeStates;
use Exception;

use Controllers\Controller;
use Models\Entities\User;
use Models\ModelDemandeConges;
use Services\ServicesCDEClient\ServiceSuivisCDEClient;
use Services\ServicesCDEFour\ServiceCDEFourH;
use Services\ServicesCDEFour\ServiceCDEFourL;
use Services\ServicesCDEFour\ServiceSuivisCDEFour;
use Services\ServicesDemandeConges\ServiceDemandeConges;
use Services\ServicesCDEClient\ServiceCDEClientH;
use Services\ServicesCDEClient\ServiceCDEClientL;
use Services\ServicesDemandeConges\ServiceSuivisDemandeConges;
use Services\ServicesDemandeDiverse\ServiceSuivisDemandeDiverse;
use Traits\Singleton;

use Models\ModelEtatModel;
use Models\ModelFournisseur;
use Models\ModelUser;
use Models\ModelCDEFourH;
use Models\ModelCDEClientH;

use Models\ModelDemandeDiverse;
use Services\ServiceCDEFournisseur;
use Services\ServiceDemandeDiverse;

use Utils\Requests\Request;
use Utils\Responses\RewindRedirectionResponse;
use Utils\Responses\RouteRedirectionResponse;
use Utils\Responses\URIRedirectionResponse;
use Utils\Session\DataHelper;
use Utils\Session\UserHelper;
use Utils\Session\ErrorHelper;

use Utils\Responses\HTMLResponse;
use Utils\Responses\Response;

use Models\EntityLists\ListEtatModel;
use Models\EntityLists\ListFournisseur;
use Models\EntityLists\ListUser;


use Controllers\Actions\ActionsSuiviDemandes;

final class ControllerSuiviDemandes extends Controller
{
	use Singleton;

	public function Route($action): Response
	{
		switch ($action) {
			case ActionsSuiviDemandes::LIST_ALL:
				return self::ListAll();

			case ActionsSuiviDemandes::LIST_DEMANDEUR:
				return self::ListDemandeur();

			case ActionsSuiviDemandes::LIST_DESTINATAIRE:
				return self::ListDestinataire();

			// diférents détails des demandes
			case ActionsSuiviDemandes::DEMANDEUR_CDE_CLIENT:
				return self::SuivisDemandeurCDEClient();

			case ActionsSuiviDemandes::DEMANDEUR_CDE_FOURNISSEUR:
				return self::SuivisDemandeurCDEFour();
			
			case ActionsSuiviDemandes::DEMANDEUR_DEMANDES_DIVERSES:
				return self::SuivisDemandeurDemandesDiverses();

			case ActionsSuiviDemandes::DEMANDEUR_DEMANDES_CONGES:
				return self::SuivisDemandeurDemandesConges();

			// diférents détails des demandes
			case ActionsSuiviDemandes::DESTINATAIRE_CDE_CLIENT:
				return self::SuivisDestinataireCDEClient();
			
			case ActionsSuiviDemandes::DESTINATAIRE_CDE_FOURNISSEUR:
				return self::SuivisDestinataireCDEFour();

			case ActionsSuiviDemandes::DESTINATAIRE_DEMANDES_DIVERSES:
				return self::SuivisDestinataireDemandesDiverses();

			case ActionsSuiviDemandes::DESTINATAIRE_DEMANDES_CONGES:
				return self::SuivisDestinataireDemandesDiverses();

			// diférents détails des demandes
			case ActionsSuiviDemandes::DETAILS_CDE_CLIENT:
				return self::DetailsCDEClient();
			
			case ActionsSuiviDemandes::DETAILS_CDE_FOURNISSEUR:
				return self::DetailsCDEFour();

			case ActionsSuiviDemandes::DETAILS_DEMANDES_DIVERSES:
				return self::DetailsDemandesDiverses();

			case ActionsSuiviDemandes::DETAILS_DEMANDES_CONGES:
				return self::DetailsDemandeConges();

			default:
				return new RouteRedirectionResponse(ControllerMenu::class);
		}
	}


	public function ListAll(): Response
	{
		if (UserHelper::GetUserId() != 1) {
			return new RouteRedirectionResponse(ControllerMenu::class, ActionsMenu::MAIN);
		}

		$Request = Request::GetRequest();

		DataHelper::Set('return_route', 'index.php?'.$Request->GetRoute());
		DataHelper::Set('view', 'details');

		//* filters
		try
		{
			$ListClientState = ModelEtatModel::GetInstance()->GetClient();
			$ListFourState = ModelEtatModel::GetInstance()->GetFournisseur();
			$ListDemandeDiverseState = ModelEtatModel::GetInstance()->GetDemandeDiverse();
			$ListDemandeur = $ListDestinataire = ModelUser::GetInstance()->GetAll();
			$ListFournisseur = ModelFournisseur::GetInstance()->GetAll();
		}
		catch (Exception $e)
		{
			$ListClientState = new ListEtatModel;
			$ListFourState = new ListEtatModel;
			$ListDemandeDiverseState = new ListEtatModel;
			$ListFournisseur = new ListFournisseur;
			$ListDestinataire = new ListEtatModel;
			$ListDestinataire = new ListEtatModel;
		}


		return new HTMLResponse(
			"public/Views/suivi_demandes/suivi_demandes_list.php",
			[
				'errors' => ErrorHelper::GetAll(),

				//
				"is_demandeur" => false,
				"is_destinataire" => false,

				// filters
				"ListClientState" => $ListClientState,
				"ListFourState" => $ListFourState,
				"ListDemandeDiverseState" => $ListDemandeDiverseState,
				"ListDestinataire" => $ListDestinataire,
				"ListDemandeur" => $ListDemandeur,
				"ListFournisseur" => $ListFournisseur,

				//default values
				"defaultStateId" => null,
			]
		);
	}


	/**
	 * Display the list of all demande where is user is the "demandeur"
	 * @return HTMLResponse
	 */
	public function ListDemandeur(): Response
	{
		$Request = Request::GetRequest();

		DataHelper::Set('return_route', 'index.php?'.$Request->GetRoute());
		DataHelper::Set('view', 'demandeur');

		//* filters
		try
		{
			$ListClientState = ModelEtatModel::GetInstance()->GetClient();
			$ListFourState = ModelEtatModel::GetInstance()->GetFournisseur();
			$ListDemandeDiverseState = ModelEtatModel::GetInstance()->GetDemandeDiverse();
			$ListDestinataire = ModelUser::GetInstance()->GetAll();
			$ListFournisseur = ModelFournisseur::GetInstance()->GetAll();
		}
		catch (Exception $e)
		{
			$ListClientState = new ListEtatModel;
			$ListFourState = new ListEtatModel;
			$ListDemandeDiverseState = new ListEtatModel;
			$ListFournisseur = new ListFournisseur;
			$ListDestinataire = new ListUser;
		}

		return new HTMLResponse(
			"public/Views/suivi_demandes/suivi_demandes_list.php",
			[
				//
				'errors' => ErrorHelper::GetAll(),

				// 
				"is_demandeur" => true,
				"is_destinataire" => false,

				// filters
				"ListClientState" => $ListClientState,
				"ListFourState" => $ListFourState,
				"ListDemandeDiverseState" => $ListDemandeDiverseState,
				"ListDestinataire" => $ListDestinataire,
				"ListDemandeur" => new ListUser,
				"ListFournisseur" => $ListFournisseur,

				// default value
				"defaultStateId" => DemandeStates::DEFAULT,
			]
		);
	}


	/**
	 * Display the list of all demande where is user is the "destinataire"
	 * 
	 * @return HTMLResponse
	 */
	public function ListDestinataire(): Response
	{
		$Request = Request::GetRequest();

		DataHelper::Set('return_route', 'index.php?'.$Request->GetRoute());
		DataHelper::Set('view', 'destinataire');

		//* filters
		try
		{
			$ListClientState = ModelEtatModel::GetInstance()->GetClient();
			$ListFourState = ModelEtatModel::GetInstance()->GetFournisseur();
			$ListDemandeDiverseState = ModelEtatModel::GetInstance()->GetDemandeDiverse();
			$ListDemandeur = ModelUser::GetInstance()->GetAll();
			$ListFournisseur = ModelFournisseur::GetInstance()->GetAll();
		}
		catch (Exception $e)
		{
			$ListClientState = new ListEtatModel;
			$ListFourState = new ListEtatModel;
			$ListDemandeDiverseState = new ListEtatModel;
			$ListFournisseur = new ListFournisseur;
			$ListDemandeur = new ListEtatModel;
		}


		// require_once "public/Views/suivi_demandes/suivi_demandes_list.php";
		return new HTMLResponse(
			"public/Views/suivi_demandes/suivi_demandes_list.php",
			[
				// 
				"errors" => ErrorHelper::GetAll(),

				//
				"is_demandeur" => false,
				"is_destinataire" => true,

				// filters
				"ListClientState" => $ListClientState,
				"ListFourState" => $ListFourState,
				"ListDemandeDiverseState" => $ListDemandeDiverseState,
				"ListDestinataire" => new ListUser,
				"ListDemandeur" => $ListDemandeur,
				"ListFournisseur" => $ListFournisseur,

				// default value
				"defaultStateId" => DemandeStates::DEFAULT,
			]
		);
	}

	
	#region Suivis Demandeur

	/**
	 * Display the Suivis Demandeur of a CDEClient
	 * 
	 * @return HTMLResponse|RouteRedirectionResponse|URIRedirectionResponse
	 */
	public function SuivisDemandeurCDEClient(): Response
	{
		ErrorHelper::Clear();

		//* verify if the demand id is valid
		$Request = Request::GetRequest();

		$id = $Request->Query->FilterInt('id') ?? DataHelper::Get('id');

		return (new ServiceSuivisCDEClient)->GetSuivisPageById(
			$id,
			new URIRedirectionResponse(DataHelper::Get("return_route") ?? RouteRedirectionResponse::PrepareURI(ControllerMenu::class))
		);
	}

	
	/**
	 * Display the Suivis Demandeur of a CDEFour
	 * 
	 * @return HTMLResponse|RouteRedirectionResponse|URIRedirectionResponse
	 */
	public function SuivisDemandeurCDEFour(): Response
	{
		ErrorHelper::Clear();

		//* verify if the demand id is valid
		$Request = Request::GetRequest();

		$id = $Request->Query->FilterInt('id') ?? DataHelper::Get('id');

		return (new ServiceSuivisCDEFour)->GetSuivisPageById(
			$id,
			new URIRedirectionResponse(DataHelper::Get("return_route") ?? RouteRedirectionResponse::PrepareURI(ControllerMenu::class))
		);
	}

	
	/**
	 * Display the Suivis Demandeur the 'demande diverse'
	 * 
	 * @return HTMLResponse|RouteRedirectionResponse|URIRedirectionResponse
	 */
	public function SuivisDemandeurDemandesDiverses(): Response
	{
		ErrorHelper::Clear();
		
		//* verify if the demand id is valid
		$Request = Request::GetRequest();
		
		$id = $Request->Query->FilterInt('id') ?? DataHelper::Get('id');

		return (new ServiceSuivisDemandeDiverse)->GetSuivisPageById(
			$id,
			new URIRedirectionResponse(DataHelper::Get("return_route") ?? RouteRedirectionResponse::PrepareURI(ControllerMenu::class))
		);
	}


	
	/**
	 * Display the Suivis Demandeur the 'demande conges'
	 * 
	 * @return HTMLResponse|RouteRedirectionResponse|URIRedirectionResponse
	 */
	public function SuivisDemandeurDemandesConges(): Response
	{
		ErrorHelper::Clear();
		
		//* verify if the demand id is valid
		$Request = Request::GetRequest();
		
		$id = $Request->Query->FilterInt('id') ?? DataHelper::Get('id');

		return (new ServiceSuivisDemandeConges)->GetSuivisPageById(
			$id,
			new URIRedirectionResponse(DataHelper::Get("return_route") ?? RouteRedirectionResponse::PrepareURI(ControllerMenu::class))
		);
	}

	#endregion Suivis Destinataire
	
	

	#region Suivis Destinataire

	/**
	 * Display the Suivis Destinataire of a CDEClient
	 * 
	 * @return HTMLResponse|RouteRedirectionResponse|URIRedirectionResponse
	 */
	public function SuivisDestinataireCDEClient(): Response
	{
		ErrorHelper::Clear();

		//* verify if the demand id is valid
		$Request = Request::GetRequest();
		$ServiceCDEClientH = new ServiceCDEClientH;

		$id = $Request->Query->FilterInt('id') ?? DataHelper::Get('id');

		return (new ServiceSuivisCDEClient)->GetSuivisPageById(
			$id,
			new URIRedirectionResponse(DataHelper::Get("return_route") ?? RouteRedirectionResponse::PrepareURI(ControllerMenu::class))
		);
	}

	
	/**
	 * Display the Suivis Destinataire of a CDEFour
	 * 
	 * @return HTMLResponse|URIRedirectionResponse|RouteRedirectionResponse
	 */
	public function SuivisDestinataireCDEFour(): Response
	{
		ErrorHelper::Clear();

		//* verify if the demand id is valid
		$Request = Request::GetRequest();

		$id = $Request->Query->FilterInt('id') ?? DataHelper::Get('id');

		switch($id) {
			case null:
				ErrorHelper::Set("cde_four", "La commande stock n'est pas précisée");
				
				return new URIRedirectionResponse(DataHelper::Get("return_route") ?? RouteRedirectionResponse::PrepareURI(ControllerMenu::class));
			
			case false:
				ErrorHelper::Set("cde_four", "Commande stock précisée est invalide");
				
				return new URIRedirectionResponse(DataHelper::Get("return_route") ?? RouteRedirectionResponse::PrepareURI(ControllerMenu::class));
		}

		//* get if the demand and verify if the user has access to it
		try
		{
			$CDEFourH = ModelCDEFourH::GetInstance()->GetById($id);
			
			if ($CDEFourH === null)
				throw new Exception("commande fournisseur not found with \"id = $id\"");
		}
		catch (Exception $e)
		{
			ErrorHelper::Set("cde_four", "Impossible de récuperer la commande stock");
			ErrorHelper::SetDebug("cde_four", $e);
			
			return new URIRedirectionResponse(DataHelper::Get("return_route") ?? RouteRedirectionResponse::PrepareURI(ControllerMenu::class));
		}

		DataHelper::Set('id', $id);
		
		//* if the user isnt the destinataire redirect him toward a view according to is rights
		if ($CDEFourH->id_etat === DemandeStates::CLOSED)
			return new RouteRedirectionResponse(self::class, ActionsSuiviDemandes::DETAILS_CDE_FOURNISSEUR);

		if ($CDEFourH->id_destinataire != UserHelper::GetUserId()) {
			if ($CDEFourH->id_demandeur == UserHelper::GetUserId())
				return new RouteRedirectionResponse(self::class, ActionsSuiviDemandes::DEMANDEUR_CDE_FOURNISSEUR);

			return new RouteRedirectionResponse(self::class, ActionsSuiviDemandes::DETAILS_CDE_FOURNISSEUR);
		}

		//* get the data
		$ServiceCDEFourH = new ServiceCDEFourH;

		$ServiceCDEFourH->AssociateAllReferences($CDEFourH);
		$ServiceCDEFourH->AssociateProduits($CDEFourH);

		foreach ($CDEFourH->ListCDEFourL as $CDEFourL)
			(new ServiceCDEFourL)->AssociateAllReferences($CDEFourL);

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
			"public/Views/suivi_demandes/suivi_destinataire_cde_four.php",
			[
				'CDEFourH' => $CDEFourH,
				"States" => $States,
				"ProductStates" => $ProductStates,
				"Destinataires" => $Destinataires,
				'errors' => ErrorHelper::GetAll(),
			]
		);
	}

	
	/**
	 * Display the Suivis Destinataire the 'demande diverse'
	 * 
	 * @return HTMLResponse|URIRedirectionResponse|RouteRedirectionResponse
	 */
	public function SuivisDestinataireDemandesDiverses(): Response
	{
		ErrorHelper::Clear();
		
		//* verify if the demand id is valid
		$Request = Request::GetRequest();
		
		$id = $Request->Query->FilterInt('id') ?? DataHelper::Get('id');

		switch($id) {
			case null:
				ErrorHelper::Set("demande_diverse", "La demande n'est pas précisée");
				
				return new URIRedirectionResponse(DataHelper::Get("return_route") ?? RouteRedirectionResponse::PrepareURI(ControllerMenu::class));
			
			case false:
				ErrorHelper::Set("demande_diverse", "Demande invalide");
				
				return new URIRedirectionResponse(DataHelper::Get("return_route") ?? RouteRedirectionResponse::PrepareURI(ControllerMenu::class));
		}

		//* get if the demand is valid and verify if the user has access to it
		try
		{
			$DemandeDiverse = ModelDemandeDiverse::GetInstance()->GetById($id);

			if ($DemandeDiverse === null)
				throw new Exception("demande diverse not found with \"id = $id\"");
		}
		catch (Exception $e)
		{
			ErrorHelper::Set("demande_diverse", "Impossible de récuperer la demande");
			ErrorHelper::SetDebug("demande_diverse", $e);
			
			return new URIRedirectionResponse(DataHelper::Get("return_route") ?? RouteRedirectionResponse::PrepareURI(ControllerMenu::class));
		}

		DataHelper::Set('id', $id);
		
		//* if the user isnt the destinataire redirect him toward a view according to is rights
		if ($DemandeDiverse->id_etat === DemandeStates::CLOSED)
			return new RouteRedirectionResponse(self::class, ActionsSuiviDemandes::DETAILS_DEMANDES_DIVERSES);

		if ($DemandeDiverse->id_destinataire != UserHelper::GetUserId()) {
			if ($DemandeDiverse->id_demandeur == UserHelper::GetUserId())
				return new RouteRedirectionResponse(self::class, ActionsSuiviDemandes::DEMANDEUR_DEMANDES_DIVERSES);

			return new RouteRedirectionResponse(self::class, ActionsSuiviDemandes::DETAILS_DEMANDES_DIVERSES);
		}

		//* get the data
		$Service = new ServiceDemandeDiverse();

		$Service->AssociateAllReferencesDemandeDiverse($DemandeDiverse);

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
			"public/Views/suivi_demandes/suivi_destinataire_demande_diverse.php",
			[
				'DemandeDiverse' => $DemandeDiverse,
				"States" => $States,
				"Destinataires" => $Destinataires,
				'errors' => ErrorHelper::GetAll(),
			]
		);
	}

	
	/**
	 * Display the Suivis Destinataire the 'demande diverse'
	 * 
	 * @return HTMLResponse|URIRedirectionResponse|RouteRedirectionResponse
	 */
	public function SuivisDestinataireDemandesConges(): Response
	{
		ErrorHelper::Clear();
		
		//* verify if the demand id is valid
		$Request = Request::GetRequest();
		
		$id = $Request->Query->FilterInt('id') ?? DataHelper::Get('id');

		switch($id) {
			case null:
				ErrorHelper::Set("demande_diverse", "La demande n'est pas précisée");
				
				return new URIRedirectionResponse(DataHelper::Get("return_route") ?? RouteRedirectionResponse::PrepareURI(ControllerMenu::class));
			
			case false:
				ErrorHelper::Set("demande_diverse", "Demande invalide");
				
				return new URIRedirectionResponse(DataHelper::Get("return_route") ?? RouteRedirectionResponse::PrepareURI(ControllerMenu::class));
		}

		//* get if the demand is valid and verify if the user has access to it
		try
		{
			$DemandeConges = ModelDemandeConges::GetInstance()->GetById($id);

			if ($DemandeConges === null)
				throw new Exception("demande diverse not found with \"id = $id\"");
		}
		catch (Exception $e)
		{
			ErrorHelper::Set("demande_diverse", "Impossible de récuperer la demande");
			ErrorHelper::SetDebug("demande_diverse", $e);
			
			return new URIRedirectionResponse(DataHelper::Get("return_route") ?? RouteRedirectionResponse::PrepareURI(ControllerMenu::class));
		}

		DataHelper::Set('id', $id);
		
		//* if the user isnt the destinataire redirect him toward a view according to is rights
		// if ($DemandeConges->id_etat === DemandeStates::CLOSED) {
		// 	return new RouteRedirectionResponse(self::class, ActionsSuiviDemandes::DETAILS_DEMANDES_DIVERSES);
		// }

		if (
			UserHelper::GetUserId() != User::DIRECTOR_1_ID 
			|| UserHelper::GetUserId() != User::DIRECTOR_2_ID 
		) {
			if ($DemandeConges->id_demandeur == UserHelper::GetUserId())
				return new RouteRedirectionResponse(self::class, ActionsSuiviDemandes::DEMANDEUR_DEMANDES_DIVERSES);

			if (UserHelper::GetUserId() != User::ADMIN_ID)
				return new RouteRedirectionResponse(self::class, ActionsSuiviDemandes::DETAILS_DEMANDES_DIVERSES);

			return new RewindRedirectionResponse;
		}

		//* get the data
		$Service = new ServiceDemandeConges;

		$Service->AssociateAllReferences($DemandeConges);

		return new HTMLResponse(
			"public/Views/suivi_demandes/suivi_destinataire_demande_conges.php",
			[
				'DemandeConges' => $DemandeConges,
				'errors' => ErrorHelper::GetAll(),
			]
		);
	}

	#endregion Suivis Destinataire


	#region details 

	/**
	 * Display the Details Destinataire of a CDEClient
	 * 
	 * @return HTMLResponse|RouteRedirectionResponse|URIRedirectionResponse
	 */
	public function DetailsCDEClient(): Response
	{
		ErrorHelper::Clear();

		//* verify if the demand id is valid
		$Request = Request::GetRequest();

		$id = $Request->Query->FilterInt('id') ?? DataHelper::Get('id');

		switch($id) {
			case null:
				ErrorHelper::Set("cde_client", "Demande client non précisée");

			return new URIRedirectionResponse(DataHelper::Get("return_route") ?? RouteRedirectionResponse::PrepareURI(ControllerMenu::class));		
			
			case false:
				ErrorHelper::Set("cde_client", "Commande/devis client précisée invalide");

				return new URIRedirectionResponse(DataHelper::Get("return_route") ?? RouteRedirectionResponse::PrepareURI(ControllerMenu::class));
		}

		//* get the demand is valid and verify if the user has access to it
		try
		{
			$CDEClientH = ModelCDEClientH::GetInstance()->GetById($id);

			if ($CDEClientH === null)
				throw new Exception("commande/devis client not found with \"id = $id\"");
		}
		catch (Exception $e)
		{
			ErrorHelper::SetDefault("cde_client", "Impossible de récuperer la demande client");
			ErrorHelper::SetDebug("cde_client", $e);

			$return_route = DataHelper::Get("return_route");
			return new URIRedirectionResponse($return_route);
		}

		DataHelper::Set('id', $id);
		
		//* if the user has more right redirect him toward that view 
		if ($CDEClientH->id_etat != DemandeStates::CLOSED) {
			if ($CDEClientH->id_destinataire == UserHelper::GetUserId())
				return new RouteRedirectionResponse(self::class, ActionsSuiviDemandes::DESTINATAIRE_CDE_CLIENT);

			if ($CDEClientH->id_demandeur == UserHelper::GetUserId())
				return new RouteRedirectionResponse(self::class, ActionsSuiviDemandes::DEMANDEUR_CDE_CLIENT);
		}

		//* get the data
		(new ServiceCDEClientH)->AssociateAllReferences($CDEClientH);

		foreach ($CDEClientH->Products as $CDEClientL)
			(new ServiceCDEClientL)->AssociateAllReferences($CDEClientL);

		DataHelper::Set('sub_action', 'index.php?controller='.self::class.'&action='.ActionsSuiviDemandes::DETAILS_CDE_CLIENT);

		return new HTMLResponse(
			"public/Views/suivi_demandes/suivi_details_cde_client.php",
			[
				"CDEClientH" => $CDEClientH,
				'errors' => ErrorHelper::GetAll(),
			]
		);
	}

	
	/**
	 * Display the Suivis Destinataire of a CDEFour
	 * 
	 * @return HTMLResponse|RouteRedirectionResponse|URIRedirectionResponse
	 */
	public function DetailsCDEFour(): Response
	{
		//* verify if the demand id is valid
		$Request = Request::GetRequest();
		$ServiceCDEFourH = new ServiceCDEFourH;

		$id = $Request->Query->FilterInt('id') ?? DataHelper::Get('id');

		switch($id) {
			case null:
				ErrorHelper::Set("cde_four", "La commande stock n'est pas précisée");
				return new URIRedirectionResponse(DataHelper::Get("return_route") ?? RouteRedirectionResponse::PrepareURI(ControllerMenu::class));

			case false:
				ErrorHelper::Set("cde_four", "Commande stock invalide");

				return new URIRedirectionResponse(DataHelper::Get("return_route") ?? RouteRedirectionResponse::PrepareURI(ControllerMenu::class));
		}

		//* get the demand
		try
		{
			$CDEFourH = $ServiceCDEFourH->Find($id);
		}
		catch (Exception $e)
		{
			ErrorHelper::Set("cde_four", "Impossible de récuperer la commande stock");
			ErrorHelper::SetDebug("cde_four", $e);

			return new URIRedirectionResponse(DataHelper::Get("return_route") ?? RouteRedirectionResponse::PrepareURI(ControllerMenu::class));
		}

		DataHelper::Set('id', $id);
		
		//* if the user has more right redirect him toward that view 
		if ($CDEFourH->id_etat != DemandeStates::CLOSED) {
			if ($CDEFourH->id_destinataire == UserHelper::GetUserId())
				return new RouteRedirectionResponse(self::class, ActionsSuiviDemandes::DESTINATAIRE_CDE_FOURNISSEUR);

			if ($CDEFourH->id_demandeur == UserHelper::GetUserId())
				return new RouteRedirectionResponse(self::class, ActionsSuiviDemandes::DEMANDEUR_CDE_FOURNISSEUR);
		}


		//* get the data

		$ServiceCDEFourH->AssociateAllReferences($CDEFourH);
		$ServiceCDEFourH->AssociateProduits($CDEFourH);

		$ServiceCDEFourL = new ServiceCDEFourL;

		foreach ($CDEFourH->ListCDEFourL as $CDEFourL)
			$ServiceCDEFourL->AssociateAllReferences($CDEFourL);

		return new HTMLResponse(
			"public/Views/suivi_demandes/suivi_details_cde_four.php",
			[
				'CDEFourH' => $CDEFourH,
				'errors' => ErrorHelper::GetAll(),
			]
		);
	}

	
	/**
	 * Display the Suivis Destinataire the 'demande diverse'
	 * 
	 * @return HTMLResponse|RouteRedirectionResponse|URIRedirectionResponse
	 */
	public function DetailsDemandesDiverses(): Response
	{
		ErrorHelper::Clear();
		
		//* verify if the demand id is valid
		$Request = Request::GetRequest();
		
		$id = $Request->Query->FilterInt('id') ?? DataHelper::Get('id');

		switch($id) {
			case null:
				ErrorHelper::Set("demande_diverse", "La demande n'est pas précisée");

				return new URIRedirectionResponse(DataHelper::Get("return_route") ?? RouteRedirectionResponse::PrepareURI(ControllerMenu::class));

			case false:
				ErrorHelper::Set("demande_diverse", "Demande invalide");

				return new URIRedirectionResponse(DataHelper::Get("return_route") ?? RouteRedirectionResponse::PrepareURI(ControllerMenu::class));
		}

		//* get if the demand is valid and verify if the user has access to it
		try
		{
			$DemandeDiverse = ModelDemandeDiverse::GetInstance()->GetById($id);

			if ($DemandeDiverse === null)
				throw new Exception("demande diverse not found with \"id = $id\"");
		}
		catch (Exception $e)
		{
			ErrorHelper::Set("demande_diverse", "Impossible de récuperer la demande");
			ErrorHelper::SetDebug("demande_diverse", $e);

			$return_route = DataHelper::Get("return_route");

			if ($return_route != null)
				return new URIRedirectionResponse($return_route);

			return new RouteRedirectionResponse(ControllerMenu::class);
		}

		DataHelper::Set('id', $id);
		
		//* if the user has more right redirect him toward that view 
		if ($DemandeDiverse->id_etat != DemandeStates::CLOSED) {
			if ($DemandeDiverse->id_destinataire == UserHelper::GetUserId())
				return new RouteRedirectionResponse(self::class, ActionsSuiviDemandes::DESTINATAIRE_DEMANDES_DIVERSES);

			if ($DemandeDiverse->id_demandeur == UserHelper::GetUserId())
				return new RouteRedirectionResponse(self::class, ActionsSuiviDemandes::DEMANDEUR_DEMANDES_DIVERSES);
		}

		//* get the data
		(new ServiceDemandeDiverse)->AssociateAllReferencesDemandeDiverse($DemandeDiverse);

		return new HTMLResponse(
			"public/Views/suivi_demandes/suivi_details_demande_diverse.php",
			[
				'DemandeDiverse' => $DemandeDiverse,
				'errors' => ErrorHelper::GetAll(),
			]
		);
	}

	
	/**
	 * Display the Suivis Destinataire the 'demande diverse'
	 * 
	 * @return HTMLResponse|RouteRedirectionResponse|URIRedirectionResponse
	 */
	public function DetailsDemandeConges(): Response
	{
		ErrorHelper::Clear();
		
		//* verify if the demand id is valid
		$Request = Request::GetRequest();
		
		$id = $Request->Query->FilterInt('id') ?? DataHelper::Get('id');

		switch($id) {
			case null:
				ErrorHelper::Set("demande_conges", "La demande de conges n'est pas précisée");
				return new URIRedirectionResponse(DataHelper::Get("return_route") ?? RouteRedirectionResponse::PrepareURI(ControllerMenu::class));

			case false:
				ErrorHelper::Set("demande_conges", "Demande de conges invalide");
				return new URIRedirectionResponse(DataHelper::Get("return_route") ?? RouteRedirectionResponse::PrepareURI(ControllerMenu::class));
		}

		//* get if the demand is valid and verify if the user has access to it
		try
		{
			$DemandeConges = ModelDemandeConges::GetInstance()->GetById($id);

			if ($DemandeConges === null)
				throw new Exception("demande diverse not found with \"id = $id\"");
		}
		catch (Exception $e)
		{
			ErrorHelper::Set("demande_conges", "Impossible de récuperer la demande de conges");
			ErrorHelper::SetDebug("demande_conges", $e);

			$return_route = DataHelper::Get("return_route");

			if ($return_route != null)
				return new URIRedirectionResponse($return_route);

			return new RouteRedirectionResponse(ControllerMenu::class);
		}

		DataHelper::Set('id', $id);
		
		//* if the user has more right redirect him toward that view 
		if ($DemandeConges->id_demandeur == UserHelper::GetUserId()) {
			return new RouteRedirectionResponse(self::class, ActionsSuiviDemandes::DEMANDEUR_DEMANDES_DIVERSES);
		}

		if (UserHelper::GetUserId() != User::ADMIN_ID) {	
			if (
				UserHelper::GetUserId() === User::DIRECTOR_1_ID
				|| UserHelper::GetUserId() === User::DIRECTOR_2_ID
			)
				return new RouteRedirectionResponse(self::class, ActionsSuiviDemandes::DESTINATAIRE_DEMANDES_DIVERSES);
			
			return new RewindRedirectionResponse;
		}

		//* get the data
		$Service = new ServiceDemandeConges;

		$Service->AssociateAllReferences($DemandeConges);

		return new HTMLResponse(
			"public/Views/suivi_demandes/suivi_details_demande_conges.php",
			[
				'DemandeConges' => $DemandeConges,
				'errors' => ErrorHelper::GetAll(),
			]
		);
	}

	#endregion Details
}