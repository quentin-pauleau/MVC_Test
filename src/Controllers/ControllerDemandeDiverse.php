<?php

namespace Controllers;

use Exception;

use Controllers\Actions\ActionsDemandeConges;

use Services\ServicesDemandeDiverse\ServiceSuivisDemandeDiverse;
use Traits\Singleton;
use Interfaces\EntityControllerInterface;

use Core\Requests\Request;

use Core\Responses\Response;
use Core\Responses\HTMLResponse;
use Core\Responses\RouteRedirectionResponse;
use Core\Responses\URIRedirectionResponse;

use Core\Session\DataHelper;
use Core\Session\ErrorHelper;


final class ControllerDemandeDiverse extends Controller implements EntityControllerInterface
{
	use Singleton;

	private const TEMP_DIR = "temp/form_demande/";
	private const COLD_DIR = "C:/nchp/var/spool/ezged/instance/wait/xpert/xpert_demandes_conges/";

	//! this is the path for the production server
	// private const COLD_DIR = "/app/spool/ocr/wait/web_form/demandes_conges";


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
			case ActionsDemandeConges::LIST_DIRECTION:
				return self::ListDirection();

			default:
				return self::Index();
		}
	}


	public function Index(): Response
	{
		throw new Exception('Not Implemented Yet');
	}


	public function List(): Response
	{
		throw new Exception('Not Implemented Yet');
	}


	public function ListDirection(): Response
	{
		throw new Exception('Not Implemented Yet');
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

		return (new ServiceSuivisDemandeDiverse)->GetSuivisPageById($id);
	}
	
	/**
	 * Redirect to the form use to create new Demande
	 * @return RouteRedirectionResponse
	 */
	public function New(): Response
	{
		return new RouteRedirectionResponse(ControllerFormDemandeDiverse::class);
	}
}