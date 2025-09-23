<?php
namespace Modules\Http\Responses;

use Modules\Http\HttpHeader;

/**
 * Immutable-like HTTP client response wrapper
 */
final class HttpResponse extends Response
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


	public function Process(): void {

		if ($this->statusCode === 301 || $this->statusCode === 302)
			$this->headers->Location($this->url);

		$this->headers->Send();

		http_response_code($this->statusCode);

		echo $this->body;
		exit;
	}




	#region 1xx Informational
	public static function Continue_(string $body = ''): self { // avoid PHP reserved word
		return new self(100, 'Continue', new HttpHeader(), $body, '');
	}
	public static function SwitchingProtocols(string $body = ''): self {
		return new self(101, 'Switching Protocols', new HttpHeader(), $body, '');
	}
	public static function Processing(string $body = ''): self {
		return new self(102, 'Processing', new HttpHeader(), $body, '');
	}
	public static function EarlyHints(string $body = ''): self {
		return new self(103, 'Early Hints', new HttpHeader(), $body, '');
	}
	#endregion 1xx Informational


	#region 2xx Success
	
	public static function Ok(string $body = ''): self {
		return new self(200, 'OK', new HttpHeader(), $body, '');
	}
	public static function Created(string $body = ''): self {
		return new self(201, 'Created', new HttpHeader(), $body, '');
	}
	public static function Accepted(string $body = ''): self {
		return new self(202, 'Accepted', new HttpHeader(), $body, '');
	}
	public static function NonAuthoritativeInformation(string $body = ''): self {
		return new self(203, 'Non-Authoritative Information', new HttpHeader(), $body, '');
	}
	public static function NoContent(string $body = ''): self {
		return new self(204, 'No Content', new HttpHeader(), $body, '');
	}
	public static function ResetContent(string $body = ''): self {
		return new self(205, 'Reset Content', new HttpHeader(), $body, '');
	}
	public static function PartialContent(string $body = ''): self {
		return new self(206, 'Partial Content', new HttpHeader(), $body, '');
	}
	public static function MultiStatus(string $body = ''): self {
		return new self(207, 'Multi-Status', new HttpHeader(), $body, '');
	}
	public static function AlreadyReported(string $body = ''): self {
		return new self(208, 'Already Reported', new HttpHeader(), $body, '');
	}
	public static function ImUsed(string $body = ''): self {
		return new self(226, 'IM Used', new HttpHeader(), $body, '');
	}
	#endregion 2xx Success


	#region 3xx Redirection
	public static function MultipleChoices(string $body = ''): self {
		return new self(300, 'Multiple Choices', new HttpHeader(), $body, '');
	}
	public static function MovedPermanently(string $body = ''): self {
		return new self(301, 'Moved Permanently', new HttpHeader(), $body, '');
	}
	public static function Found(string $body = ''): self {
		return new self(302, 'Found', new HttpHeader(), $body, '');
	}
	public static function SeeOther(string $body = ''): self {
		return new self(303, 'See Other', new HttpHeader(), $body, '');
	}
	public static function NotModified(string $body = ''): self {
		return new self(304, 'Not Modified', new HttpHeader(), $body, '');
	}
	public static function UseProxy(string $body = ''): self {
		return new self(305, 'Use Proxy', new HttpHeader(), $body, '');
	}
	public static function Unused(string $body = ''): self {
		return new self(306, 'Unused', new HttpHeader(), $body, '');
	}
	public static function TemporaryRedirect(string $body = ''): self {
		return new self(307, 'Temporary Redirect', new HttpHeader(), $body, '');
	}
	public static function PermanentRedirect(string $body = ''): self {
		return new self(308, 'Permanent Redirect', new HttpHeader(), $body, '');
	}

	#endregion 3xx Redirection


	#region 4xx Client errors
	public static function BadRequest(string $body = ''): self {
		return new self(400, 'Bad Request', new HttpHeader(), $body, '');
	}
	public static function Unauthorized(string $body = ''): self {
		return new self(401, 'Unauthorized', new HttpHeader(), $body, '');
	}
	public static function PaymentRequired(string $body = ''): self {
		return new self(402, 'Payment Required', new HttpHeader(), $body, '');
	}
	public static function Forbidden(string $body = ''): self {
		return new self(403, 'Forbidden', new HttpHeader(), $body, '');
	}
	public static function NotFound(string $body = ''): self {
		return new self(404, 'Not Found', new HttpHeader(), $body, '');
	}
	public static function MethodNotAllowed(string $body = ''): self {
		return new self(405, 'Method Not Allowed', new HttpHeader(), $body, '');
	}
	public static function NotAcceptable(string $body = ''): self {
		return new self(406, 'Not Acceptable', new HttpHeader(), $body, '');
	}
	public static function ProxyAuthenticationRequired(string $body = ''): self {
		return new self(407, 'Proxy Authentication Required', new HttpHeader(), $body, '');
	}
	public static function RequestTimeout(string $body = ''): self {
		return new self(408, 'Request Timeout', new HttpHeader(), $body, '');
	}
	public static function Conflict(string $body = ''): self {
		return new self(409, 'Conflict', new HttpHeader(), $body, '');
	}
	public static function Gone(string $body = ''): self {
		return new self(410, 'Gone', new HttpHeader(), $body, '');
	}
	public static function LengthRequired(string $body = ''): self {
		return new self(411, 'Length Required', new HttpHeader(), $body, '');
	}
	public static function PreconditionFailed(string $body = ''): self {
		return new self(412, 'Precondition Failed', new HttpHeader(), $body, '');
	}
	public static function PayloadTooLarge(string $body = ''): self {
		return new self(413, 'Payload Too Large', new HttpHeader(), $body, '');
	}
	public static function UriTooLong(string $body = ''): self {
		return new self(414, 'URI Too Long', new HttpHeader(), $body, '');
	}
	public static function UnsupportedMediaType(string $body = ''): self {
		return new self(415, 'Unsupported Media Type', new HttpHeader(), $body, '');
	}
	public static function RangeNotSatisfiable(string $body = ''): self {
		return new self(416, 'Range Not Satisfiable', new HttpHeader(), $body, '');
	}
	public static function ExpectationFailed(string $body = ''): self {
		return new self(417, 'Expectation Failed', new HttpHeader(), $body, '');
	}
	public static function ImATeapot(string $body = ''): self {
		return new self(418, "I'm a teapot", new HttpHeader(), $body, '');
	}
	public static function MisdirectedRequest(string $body = ''): self {
		return new self(421, 'Misdirected Request', new HttpHeader(), $body, '');
	}
	public static function UnprocessableEntity(string $body = ''): self {
		return new self(422, 'Unprocessable Entity', new HttpHeader(), $body, '');
	}
	public static function Locked(string $body = ''): self {
		return new self(423, 'Locked', new HttpHeader(), $body, '');
	}
	public static function FailedDependency(string $body = ''): self {
		return new self(424, 'Failed Dependency', new HttpHeader(), $body, '');
	}
	public static function TooEarly(string $body = ''): self {
		return new self(425, 'Too Early', new HttpHeader(), $body, '');
	}
	public static function UpgradeRequired(string $body = ''): self {
		return new self(426, 'Upgrade Required', new HttpHeader(), $body, '');
	}
	public static function PreconditionRequired(string $body = ''): self {
		return new self(428, 'Precondition Required', new HttpHeader(), $body, '');
	}
	public static function TooManyRequests(string $body = ''): self {
		return new self(429, 'Too Many Requests', new HttpHeader(), $body, '');
	}
	public static function RequestHeaderFieldsTooLarge(string $body = ''): self {
		return new self(431, 'Request Header Fields Too Large', new HttpHeader(), $body, '');
	}
	public static function UnavailableForLegalReasons(string $body = ''): self {
		return new self(451, 'Unavailable For Legal Reasons', new HttpHeader(), $body, '');
	}
	#endregion 4xx Client errors


	#region 5xx Server errors

	public static function InternalServerError(string $body = ''): self {
		return new self(500, 'Internal Server Error', new HttpHeader(), $body, '');
	}
	public static function NotImplemented(string $body = ''): self {
		return new self(501, 'Not Implemented', new HttpHeader(), $body, '');
	}
	public static function BadGateway(string $body = ''): self {
		return new self(502, 'Bad Gateway', new HttpHeader(), $body, '');
	}
	public static function ServiceUnavailable(string $body = ''): self {
		return new self(503, 'Service Unavailable', new HttpHeader(), $body, '');
	}
	public static function GatewayTimeout(string $body = ''): self {
		return new self(504, 'Gateway Timeout', new HttpHeader(), $body, '');
	}
	public static function HttpVersionNotSupported(string $body = ''): self {
		return new self(505, 'HTTP Version Not Supported', new HttpHeader(), $body, '');
	}
	public static function VariantAlsoNegotiates(string $body = ''): self {
		return new self(506, 'Variant Also Negotiates', new HttpHeader(), $body, '');
	}
	public static function InsufficientStorage(string $body = ''): self {
		return new self(507, 'Insufficient Storage', new HttpHeader(), $body, '');
	}
	public static function LoopDetected(string $body = ''): self {
		return new self(508, 'Loop Detected', new HttpHeader(), $body, '');
	}
	public static function BandwidthLimitExceeded(string $body = ''): self {
		return new self(509, 'Bandwidth Limit Exceeded', new HttpHeader(), $body, '');
	}
	public static function NotExtended(string $body = ''): self {
		return new self(510, 'Not Extended', new HttpHeader(), $body, '');
	}
	public static function NetworkAuthenticationRequired(string $body = ''): self {
		return new self(511, 'Network Authentication Required', new HttpHeader(), $body, '');
	}

	#endregion 5xx Server errors

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