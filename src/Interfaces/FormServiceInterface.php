<?php
namespace Interfaces;

use Utils\Requests\Request;

interface FormServiceInterface extends ServiceInterface
{
	/**
	 * Handle the form data
	 * @param Request $Request The request object
	 * @param object $output the output object to be filled with the form data
	 * @return true if the form is valid, false otherwise
	 */
	public function Handle(Request $Request, object $output): bool;


}