<?php
namespace Modules\Http\Responses;

use Modules\Http\HttpHeader;

/**
 * Redirect the user to another route or URI
 */
final class RedirectionResponse extends Response
{

	public function __construct(
		protected ?string $uri = null,
		protected ?HttpHeader $header = null,
		protected string $body = '',
	) {}

	/**
	 * Send the redirection
	 * @return never
	 */
	public function Send(): void {
		(HttpResponse::TemporaryRedirect(
			$this->body,
			$this->header
		))->Send();
		exit;
	}

	public static function Rewind(
		?HttpHeader $header = null,
		string $body = '',
	): RedirectionResponse
	{
		return new self(
			$_SERVER['HTTP_REFERER'],
			$header,
			$body,
		);
	}

	public static function Back(
		?HttpHeader $header = null,
		string $body = '',
	): RedirectionResponse
	{
		return new self(
			null,
			$header,
			$body,
		);
	}

	public static function FromAction(
		?HttpHeader $header = null,
		string $body = '',
	): RedirectionResponse
	{
		return new self(
			null,
			$header,
			$body,
		);
	}
}