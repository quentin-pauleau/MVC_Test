<?php
namespace Src\Controllers;

use Modules\Routing\Action\Action;
use Modules\Routing\Controller\AbstractController;
use Modules\Routing\Controller\Controller;
use Modules\Routing\Route\Get;

#[Controller("default")]
class DefaultController extends AbstractController
{
	#[Action("default_index")]
	#[Get("")]
	public function index(): string {
		return "Hello World!";
	}


	#[Action("default_about")]
	#[Get("/about")]
	public function about(): string {
		return "About page";
	}


	#[Action("error_404")]
	#[Get("/error-404")]
	public function error404(): string {
		return "Error 404";
	}
}