<?php
namespace Modules\Http\Responses;

use Modules\Http\HttpHeader;

class XmlResponse extends Response
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
	public function Process(): void {
		$header = $this->headers ?? HttpHeader::Create();
		$header->Xml();
		$header->Send(true, $this->statusCode);

		echo $this->content;
		exit;
	}
}