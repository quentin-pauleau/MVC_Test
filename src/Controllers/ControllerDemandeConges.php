<?php

namespace Controllers;

use Exception;
use DateTime;

use Controllers\Actions\ActionsDemandeConges;

use Enums\DemandeCongesStates;
use Traits\Singleton;
use Interfaces\EntityControllerInterface;

use Services\ServicesDemandeConges\ServiceSuivisDemandeConges;

use Models\EntityLists\ListTypeConges;
use Models\EntityLists\ListUser;
use Models\Entities\User;

use Models\ModelTypeConges;
use Models\ModelUser;

use Core\Requests\Request;

use Core\Responses\Response;
use Core\Responses\HTMLResponse;
use Core\Responses\RouteRedirectionResponse;
use Core\Responses\URIRedirectionResponse;
use Core\Responses\RewindRedirectionResponse;

use Core\Session\DataHelper;
use Core\Session\ErrorHelper;
use Core\Session\UserHelper;


final class ControllerDemandeConges extends Controller implements EntityControllerInterface
{
	use Singleton;

	public function Route($action): Response
	{
		//* select the right route
		switch ($action) {
			case ActionsDemandeConges::INDEX:
				return self::Index();

			case ActionsDemandeConges::DETAILS:
				return self::Details();

			case ActionsDemandeConges::LIST:
				return self::List();

			//* Suivis
			case ActionsDemandeConges::LIST_DEMANDEUR:
				return self::ListDemandeur();

			case ActionsDemandeConges::LIST_DIRECTION:
				return self::ListDirection();

			default:
				return self::Index();
		}
	}


	public function Index(): Response
	{
		return $this->ListDemandeur();
	}


	public function List(): Response
	{
		if (!UserHelper::GetUser()->IsAdmin()) {
			if (!UserHelper::GetUser()->IsDirector())
				return new RouteRedirectionResponse(self::class, ActionsDemandeConges::LIST_DEMANDEUR);

			return new RouteRedirectionResponse(self::class, ActionsDemandeConges::LIST_DIRECTION);
		}

		try
		{
			$Users = ModelUser::GetInstance()->GetAll();
			$TypesConges = ModelTypeConges::GetInstance()->GetActives();
		}
		catch (Exception $e)
		{
			ErrorHelper::Set('', $e);
		}
		
		$DemandeCongesStates = [
			DemandeCongesStates::PENDING,
			DemandeCongesStates::DENIED,
			DemandeCongesStates::ACCEPTED,
			DemandeCongesStates::CANCELED,
		];

		DataHelper::Set("return_route", RouteRedirectionResponse::PrepareURI(self::class, ActionsDemandeConges::LIST));

		return new HTMLResponse(
			'public/Views/demande_conges/demande_conges_list.php',
			[
				'errors' => ErrorHelper::GetAll(),

				// default filter values
				'is_demandeur' => false,
				'defaultDemandeurId' => null,
				'defaultStateId' => null,
				'DefaultStartDate' => null,
				'DefaultEndDate' => (new DateTime())->setTime(0, 0),

				// possible filter values
				'Demandeurs' => $Users ?? new ListUser,
				'TypesConges' => $TypesConges ?? new ListTypeConges,
				'DemandeCongesStates' => $DemandeCongesStates,
			]
		);
	}


	public function ListDemandeur(): Response
	{
		try
		{
			$Users = ModelUser::GetInstance()->GetAll();
			$TypesConges = ModelTypeConges::GetInstance()->GetActives();
		}
		catch (Exception $e)
		{
			ErrorHelper::Set('', $e);
		}
		
		$DemandeCongesStates = [
			DemandeCongesStates::PENDING,
			DemandeCongesStates::DENIED,
			DemandeCongesStates::ACCEPTED,
			DemandeCongesStates::CANCELED,
		];

		DataHelper::Set("return_route", RouteRedirectionResponse::PrepareURI(self::class, ActionsDemandeConges::LIST_DEMANDEUR));

		return new HTMLResponse(
			'public/Views/demande_conges/demande_conges_list.php',
			[
				'errors' => ErrorHelper::GetAll(),

				// default filter values
				'is_demandeur' => true,
				'defaultDemandeurId' => UserHelper::GetUserId(),
				'defaultStateId' => null,
				'DefaultStartDate' => null,
				'DefaultEndDate' => (new DateTime())->setTime(0, 0),

				// possible filter values
				'Demandeurs' => $Users ?? new ListUser,
				'TypesConges' => $TypesConges ?? new ListTypeConges,
				'DemandeCongesStates' => $DemandeCongesStates,
			]
		);
	}


	public function ListDirection(): Response
	{
		//* verify if the user has the right to access this page
		if (!UserHelper::GetUser()->IsDirector())
			return new RewindRedirectionResponse;


		//* get the data
		try
		{
			$Users = ModelUser::GetInstance()->GetAll();
			$TypesConges = ModelTypeConges::GetInstance()->GetActives();
		}
		catch (Exception $e)
		{
			ErrorHelper::Set('', $e);
		}
		
		$DemandeCongesStates = [];

		switch (UserHelper::GetUserId()) {
			case User::DIRECTOR_1_ID:
				$DemandeCongesStates = [
					DemandeCongesStates::NOT_TREATED_1,
					DemandeCongesStates::TREATED_1,
					DemandeCongesStates::DENIED,
					DemandeCongesStates::ACCEPTED,
					DemandeCongesStates::CANCELED,
				];
				break;
			case User::DIRECTOR_2_ID:
				$DemandeCongesStates = [
					DemandeCongesStates::NOT_TREATED_2,
					DemandeCongesStates::TREATED_2,
					DemandeCongesStates::DENIED,
					DemandeCongesStates::ACCEPTED,
					DemandeCongesStates::CANCELED,
				];
				break;

			default:
				$DemandeCongesStates = [
					DemandeCongesStates::PENDING,
					DemandeCongesStates::DENIED,
					DemandeCongesStates::ACCEPTED,
					DemandeCongesStates::PENDING,
				];
				break;
		}

		DataHelper::Set("return_route", RouteRedirectionResponse::PrepareURI(self::class, ActionsDemandeConges::LIST_DIRECTION));

		return new HTMLResponse(
			'public/Views/demande_conges/demande_conges_list.php',
			[
				'errors' => ErrorHelper::GetAll(),

				// default filter values
				'is_demandeur' => false,
				'defaultDemandeurId' => null,
				'defaultStateId' => null,
				'DefaultStartDate' => null,
				'DefaultEndDate' => (new DateTime())->setTime(0, 0),

				// possible filter values
				'Demandeurs' => $Users ?? new ListUser,
				'TypesConges' => $TypesConges ?? new ListTypeConges,
				'DemandeCongesStates' => $DemandeCongesStates,
			]
		);
	}


	/**
	 * Display the Suivis Destinataire the 'demande diverse'
	 * 
	 * @return HTMLResponse|RouteRedirectionResponse|URIRedirectionResponse
	 */
	public function Details(): Response
	{
		ErrorHelper::Clear();
		
		//* verify if the demand id is valid
		$Request = Request::GetRequest();
		
		$id = $Request->Query->FilterInt('id') ?? DataHelper::Get('id');

		return (new ServiceSuivisDemandeConges)->GetSuivisPageById($id);
	}
	
	/**
	 * Redirect to the form use to create new Demande
	 * @return RouteRedirectionResponse
	 */
	public function New(): Response
	{
		return new RouteRedirectionResponse(ControllerFormDemandeConges::class);
	}
}