<?php
namespace Modules\Http\Responses;

use Modules\Http\HttpHeader;

/**
 * Display a php/html view, based on its path and the given data
 */
class ViewResponse extends Response
{
	private string $path = '';
	private array $data = [];
	private ?HttpHeader $headers = null;
	private int $statusCode = 200;

	public function __construct(string $path, array $data = [], ?HttpHeader $headers = null, int $statusCode = 200)
	{
		$this->path = $path;
		$this->data = $data;
		$this->headers = $headers;
		$this->statusCode = $statusCode;
	}

	/**
	 * Unset all variables and display the template with all
	 * @return never
	 */
	public function Process(): void {
		$header = $this->headers ?? HttpHeader::Create();
		$header->Html();
		$header->Send(true, $this->statusCode);

		extract($this->data);
		require_once $this->path;
		exit;
	}
}