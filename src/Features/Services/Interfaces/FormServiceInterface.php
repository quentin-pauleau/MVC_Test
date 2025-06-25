<?php
namespace Feature\Services\Interfaces;

use Core\Requests\Request;
use Core\Responses\HTMLResponse;

/**
 * A interface for form handling services
 */
interface FormServiceInterface extends ServiceInterface
{
	/**
	 * Load the form in a page
	 * @return bool true if loaded successfully, false otherwise
	 */
	public function Load(): bool;
	

	/**
	 * Get the form as an html page
	 * @return HTMLResponse the form page
	 */
	public function GetPage(): HTMLResponse;


	/**
	 * Handle the form data and set it into an object
	 * 
	 * @param Request $Request The request object
	 * @param object $output the output object to be filled with the form data
	 * @return true if the form is valid, false otherwise
	 */
	public function Handle(Request $Request, object $output): bool;
}