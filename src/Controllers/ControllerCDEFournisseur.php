<?php

namespace Controllers;

use Controllers\Actions\ActionsFormCDEClient;
use Exception;

use Controllers\Actions\ActionsCDEFournisseur;

use Services\ServicesCDEFour\ServiceSuivisCDEFour;
use Traits\Singleton;
use Interfaces\EntityControllerInterface;

use Core\Requests\Request;

use Core\Responses\Response;
use Core\Responses\HTMLResponse;
use Core\Responses\RouteRedirectionResponse;
use Core\Responses\URIRedirectionResponse;

use Core\Session\DataHelper;
use Core\Session\ErrorHelper;


final class ControllerCDEFournisseur extends Controller implements EntityControllerInterface
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
			case ActionsCDEFournisseur::INDEX:
				return $this->Index();

			case ActionsCDEFournisseur::DETAILS:
				return $this->Details();

			case ActionsCDEFournisseur::LIST:
				return $this->List();
			
			case ActionsCDEFournisseur::NEW:
				return $this->New();

			//* Suivis
			case ActionsCDEFournisseur::LIST_DEMANDEUR:
				return $this->ListDemandeur();

			case ActionsCDEFournisseur::LIST_DESTINATAIRE:
				return $this->ListDestinataire();

			default:
				return $this->Index();
		}
	}


	public function Index(): Response
	{
		return $this->List();
	}


	public function List(): Response
	{
		throw new Exception('Not Implemented Yet');
	}


	public function ListDemandeur(): Response
	{
		throw new Exception('Not Implemented Yet');
	}


	public function ListDestinataire(): Response
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

		return (new ServiceSuivisCDEFour)->GetSuivisPageById($id);
	}
	
	/**
	 * Redirect to the form use to create new Demande
	 * @return RouteRedirectionResponse
	 */
	public function New(): Response
	{
		return new RouteRedirectionResponse(ControllerFormCDEFournisseur::class);
	}
}