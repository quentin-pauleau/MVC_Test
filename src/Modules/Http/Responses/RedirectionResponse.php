<?php
namespace Core\Responses;

use Modules\Http\HttpHeader;

/**
 * Redirect the user to another route or URI
 */
abstract class RedirectionResponse extends Response
{
	protected ?string $uri = null;
	protected int $statusCode = 302;
	protected ?HttpHeader $headers = null;

	public function __construct(?string $uri = null, int $statusCode = 302, ?HttpHeader $headers = null)
	{
		$this->uri = $uri;
		$this->statusCode = $statusCode;
		$this->headers = $headers;
	}

	/**
	 * Process the redirection
	 * @return never
	 */
	final public function Process(): void {
		$header = $this->headers ?? HttpHeader::Create();
		
		if ($this->uri)
			$header->Location($this->uri);
		
		$header->Send(true, $this->statusCode);
		exit;
	}
}