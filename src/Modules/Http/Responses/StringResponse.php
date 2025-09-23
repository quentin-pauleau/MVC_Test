<?php
namespace Modules\Http\Responses;

use Modules\Http\HttpHeader;

/**
 * Display a text from a string
 */
class TextResponse extends Response
{
	private string $text = '';
	private ?HttpHeader $headers = null;
	private int $statusCode = 200;

	public function __construct(string $text, ?HttpHeader $headers = null, int $statusCode = 200)
	{
		$this->text = $text;
		$this->headers = $headers;
		$this->statusCode = $statusCode;
	}

	/**
	 * Unset all variables and display the template with all
	 * @return never
	 */
	public function Process(): void {
		$header = $this->headers ?? HttpHeader::Create();
		$header->Text();
		$header->Send(true, $this->statusCode);
		echo $this->text;
		exit;
	}
}