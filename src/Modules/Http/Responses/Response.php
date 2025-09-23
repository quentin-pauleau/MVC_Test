<?php
namespace Modules\Http\Responses;


/**
 * A Response send by the controller to the app core
 * 
 * All response types : 
 * - {@see HTMLResponse} Display a html/php view
 * - {@see JSONResponse} Display json data
 * - {@see StringResponse} Display a text
 * - {@see RedirectionResponse} Redirect to a given route
 * - {@see RewindRedirectionResponse} Redirect to the previous route
 */
abstract class Response
{
	/**
	 * Process the response
	 * @return never this function must end the program
	 */
	abstract public function Process(): void;
}