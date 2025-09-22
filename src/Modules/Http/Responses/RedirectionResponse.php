<?php
namespace Core\Responses;

/**
 * Redirect the user to another route based of a given controller and action
 * 
 * The static function {@see self::ToURI} can be use to redirect to any link
 */
abstract class RedirectionResponse extends Response
{
	protected ?string $uri = null;
	
	/**
	 * Process the redirection to an other page
	 * @return never
	 */
	final public function Process(): void {
		header("Location: $this->uri");
		exit;
	}
}