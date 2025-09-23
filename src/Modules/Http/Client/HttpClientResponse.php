<?php
namespace Modules\Http;

/**
 * Immutable-like HTTP client response wrapper
 */
final class HttpClientResponse
{
	private int $statusCode;
	private string $reasonPhrase;
	private HttpHeader $headers;
	private string $body;
	private string $url;

	/**
	 * @param int $statusCode
	 * @param string $reasonPhrase
	 * @param HttpHeader $headers
	 * @param string $body
	 * @param string $url
	 */
	public function __construct(int $statusCode, string $reasonPhrase, HttpHeader $headers, string $body, string $url)
	{
		$this->statusCode = $statusCode;
		$this->reasonPhrase = $reasonPhrase;
		$this->headers = $headers;
		$this->body = $body;
		$this->url = $url;
	}


	
	#region 2xx errors
	public static function Ok(): self {
		return new self(200, 'OK', new HttpHeader(), '', '');
	}


	public static function Created(): self {
		return new self(201, 'Created', new HttpHeader(), '', '');
	}

	public static function Accepted(): self {
		return new self(202, 'Accepted', new HttpHeader(), '', '');
	}

	

	public static function NoContent(): self {
		return new self(204, 'No Content', new HttpHeader(), '', '');
	}

	public static function MovedPermanently(): self {
		return new self(301, 'Moved Permanently', new HttpHeader(), '', '');
	}

	public static function TemporaryRedirect(): self {
		return new self(307, 'Temporary Redirect', new HttpHeader(), '', '');
	}

	public static function PermanentRedirect(): self {
		return new self(308, 'Permanent Redirect', new HttpHeader(), '', '');
	}

	public static function NotModified(): self {
		return new self(304, 'Not Modified', new HttpHeader(), '', '');
	}

	#region 4xx errors
	public static function BadRequest(): self {
		return new self(400, 'Bad Request', new HttpHeader(), '', '');
	}

	public static function Unauthorized(): self {
		return new self(401, 'Unauthorized', new HttpHeader(), '', '');
	}

	public static function NotFound(): self {
		return new self(404, 'Not Found', new HttpHeader(), '', '');
	}

	public static function Forbidden(): self {
		return new self(403, 'Forbidden', new HttpHeader(), '', '');
	}

	public static function MethodNotAllowed(): self {
		return new self(405, 'Method Not Allowed', new HttpHeader(), '', '');
	}

	public static function Conflict(): self {
		return new self(409, 'Conflict', new HttpHeader(), '', '');
	}

	public static function UnprocessableEntity(): self {
		return new self(422, 'Unprocessable Entity', new HttpHeader(), '', '');
	}

	public static function TooManyRequests(): self {
		return new self(429, 'Too Many Requests', new HttpHeader(), '', '');
	}

	public static function ServiceUnavailable(): self {
		return new self(503, 'Service Unavailable', new HttpHeader(), '', '');
	}

	#endregion


	public function getStatusCode(): int { return $this->statusCode; }
	public function getReasonPhrase(): string { return $this->reasonPhrase; }
	public function getHeaders(): HttpHeader { return $this->headers; }
	public function getBody(): string { return $this->body; }
	public function getUrl(): string { return $this->url; }

	public function isSuccessful(): bool
	{
		return $this->statusCode >= 200 && $this->statusCode < 300;
	}

	public function header(string $name, ?string $default = null): ?string
	{
		return $this->headers->Get($name, $default);
	}

	public function contentType(): ?string
	{
		return $this->header('Content-Type');
	}

	public function json(bool $assoc = true)
	{
		return json_decode($this->body, $assoc);
	}

	public function text(): string
	{
		return $this->body;
	}
}