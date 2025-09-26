<?php
namespace Modules\Http\Responses;

use Modules\Http\HttpHeader;

/**
 * Display a php/html view, based on its path and the given data
 */
class HTMLResponse extends Response
{

	public function __construct(
		private string $content = '',
		private ?HttpHeader $headers = null,
		private int $statusCode = 200,
	) {}

	/**
	 * Unset all variables and display the template with all
	 * @return never
	 */
	public function Send(): void {
		$header = $this->headers ?? HttpHeader::Create();
		$header->Html();
		$header->Send(true, $this->statusCode);

		echo $this->content;
		exit;
	}
}