<?php
namespace Src\Controllers;

use Modules\Http\Responses\ViewResponse;
use Modules\Http\Responses\Response;
use Modules\Routing\Action\Action;
use Modules\Routing\Controller\AbstractController;
use Modules\Routing\Controller\Controller;
use Modules\Routing\Route\Get;

#[Controller("default")]
class DefaultController extends AbstractController
{
	#[Action("default_index")]
	#[Get("")]
	public function index(): Response
	{
		return new ViewResponse(
			''
		);
	}


	#[Action("default_about")]
	#[Get("/about")]
	public function about(): Response {
		return new ViewResponse(
			''
		);
	}


	#[Action("error_404")]
	#[Get("/error-404")]
	public function error404(): Response {
		return new ViewResponse(
			''
		);
	}
}