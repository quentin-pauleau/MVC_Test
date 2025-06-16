<?php
namespace Utils\Responses;

class URIRedirectionResponse extends RedirectionResponse
{
	/**
	 * Create a redirection response based of a given controller and action
	 * @param string $uri the uri the user should be redirected to
	 */
	public function __construct(string $uri) {
		$this->uri = $uri;
	}
}