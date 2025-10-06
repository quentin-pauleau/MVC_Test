<?php
namespace Modules\Http\Responses;

use Modules\Http\HttpHeader;

/**
 * Immutable-like HTTP client response wrapper
 */
final class HttpResponse extends Response
{
	/**
	 * @param int $statusCode
	 * @param HttpHeader|null $headers
	 * @param string $body
	 * @param string|null $url
	 */
	public function __construct(
		private int $statusCode,
		private ?HttpHeader $headers = null,
		private string $body = '',
		private ?string $url = null,
	) {}


	public function Send(): void {
		$this->headers ??= new HttpHeader;

		if ($this->statusCode === 301 || $this->statusCode === 302)
			$this->headers->Location($this->url);

		$this->headers->Send();

		http_response_code($this->statusCode);

		echo $this->body;
		exit;
	}




	#region 1xx Informational
	public static function Continue_(string $body = 'Continue', ?HttpHeader $header = null): self { // avoid PHP reserved word
		return new self(100, $header, $body, '');
	}
	public static function SwitchingProtocols(string $body = 'Switching Protocols', ?HttpHeader $header = null): self {
		return new self(101, $header, $body, '');
	}
	public static function Sending(string $body = 'Sending', ?HttpHeader $header = null): self {
		return new self(102, $header, $body, '');
	}
	public static function EarlyHints(string $body = 'Early Hints', ?HttpHeader $header = null): self {
		return new self(103, $header, $body, '');
	}
	#endregion 1xx Informational


	#region 2xx Success
	
	public static function Ok(string $body = 'OK', ?HttpHeader $header = null): self {
		return new self(200, $header, $body, '');
	}
	public static function Created(string $body = 'Created', ?HttpHeader $header = null): self {
		return new self(201, $header, $body, '');
	}
	public static function Accepted(string $body = 'Accepted', ?HttpHeader $header = null): self {
		return new self(202, $header, $body, '');
	}
	public static function NonAuthoritativeInformation(string $body = 'Non-Authoritative Information', ?HttpHeader $header = null): self {
		return new self(203, $header, $body, '');
	}
	public static function NoContent(string $body = 'No Content', ?HttpHeader $header = null): self {
		return new self(204, $header, $body, '');
	}
	public static function ResetContent(string $body = 'Reset Content', ?HttpHeader $header = null): self {
		return new self(205, $header, $body, '');
	}
	public static function PartialContent(string $body = 'Partial Content', ?HttpHeader $header = null): self {
		return new self(206, $header, $body, '');
	}
	public static function MultiStatus(string $body = 'Multi-Status', ?HttpHeader $header = null): self {
		return new self(207, $header, $body, '');
	}
	public static function AlreadyReported(string $body = 'Already Reported', ?HttpHeader $header = null): self {
		return new self(208, $header, $body, '');
	}
	public static function ImUsed(string $body = 'IM Used', ?HttpHeader $header = null): self {
		return new self(226, $header, $body, '');
	}
	#endregion 2xx Success


	#region 3xx Redirection
	public static function MultipleChoices(string $body = 'Multiple Choices', ?HttpHeader $header = null): self {
		return new self(300, $header, $body, '');
	}
	public static function MovedPermanently(string $body = 'Moved Permanently', ?HttpHeader $header = null): self {
		return new self(301, $header, $body, '');
	}
	public static function Found(string $body = 'Found', ?HttpHeader $header = null): self {
		return new self(302, $header, $body, '');
	}
	public static function SeeOther(string $body = 'See Other', ?HttpHeader $header = null): self {
		return new self(303, $header, $body, '');
	}
	public static function NotModified(string $body = 'Not Modified', ?HttpHeader $header = null): self {
		return new self(304, $header, $body, '');
	}
	public static function UseProxy(string $body = 'Use Proxy', ?HttpHeader $header = null): self {
		return new self(305, $header, $body, '');
	}
	public static function Unused(string $body = 'Unused', ?HttpHeader $header = null): self {
		return new self(306, $header, $body, '');
	}
	public static function TemporaryRedirect(string $body = 'Temporary Redirect', ?HttpHeader $header = null): self {
		return new self(307, $header, $body, '');
	}
	public static function PermanentRedirect(string $body = 'Permanent Redirect', ?HttpHeader $header = null): self {
		return new self(308, $header, $body, '');
	}

	#endregion 3xx Redirection


	#region 4xx Client errors
	public static function BadRequest(string $body = 'Bad Request', ?HttpHeader $header = null): self {
		return new self(400, $header, $body, '');
	}
	public static function Unauthorized(string $body = 'Unauthorized', ?HttpHeader $header = null): self {
		return new self(401, $header, $body, '');
	}
	public static function PaymentRequired(string $body = 'Payment Required', ?HttpHeader $header = null): self {
		return new self(402, $header, $body, '');
	}
	public static function Forbidden(string $body = 'Forbidden', ?HttpHeader $header = null): self {
		return new self(403, $header, $body, '');
	}
	public static function NotFound(string $body = 'Not Found', ?HttpHeader $header = null): self {
		return new self(404, $header, $body, '');
	}
	public static function MethodNotAllowed(string $body = 'Method Not Allowed', ?HttpHeader $header = null): self {
		return new self(405, $header, $body, '');
	}
	public static function NotAcceptable(string $body = 'Not Acceptable', ?HttpHeader $header = null): self {
		return new self(406, $header, $body, '');
	}
	public static function ProxyAuthenticationRequired(string $body = 'Proxy Authentication Required', ?HttpHeader $header = null): self {
		return new self(407, $header, $body, '');
	}
	public static function RequestTimeout(string $body = 'Request Timeout', ?HttpHeader $header = null): self {
		return new self(408, $header, $body, '');
	}
	public static function Conflict(string $body = 'Conflict', ?HttpHeader $header = null): self {
		return new self(409, $header, $body, '');
	}
	public static function Gone(string $body = 'Gone', ?HttpHeader $header = null): self {
		return new self(410, $header, $body, '');
	}
	public static function LengthRequired(string $body = 'Length Required', ?HttpHeader $header = null): self {
		return new self(411, $header, $body, '');
	}
	public static function PreconditionFailed(string $body = 'Precondition Failed', ?HttpHeader $header = null): self {
		return new self(412, $header, $body, '');
	}
	public static function PayloadTooLarge(string $body = 'Payload Too Large', ?HttpHeader $header = null): self {
		return new self(413, $header, $body, '');
	}
	public static function UriTooLong(string $body = 'URI Too Long', ?HttpHeader $header = null): self {
		return new self(414, $header, $body, '');
	}
	public static function UnsupportedMediaType(string $body = 'Unsupported Media Type', ?HttpHeader $header = null): self {
		return new self(415, $header, $body, '');
	}
	public static function RangeNotSatisfiable(string $body = 'Range Not Satisfiable', ?HttpHeader $header = null): self {
		return new self(416, $header, $body, '');
	}
	public static function ExpectationFailed(string $body = 'Expectation Failed', ?HttpHeader $header = null): self {
		return new self(417, $header, $body, '');
	}
	public static function ImATeapot(string $body = "I'm a teapot", ?HttpHeader $header = null): self {
		return new self(418,  $header, $body, '');
	}
	public static function MisdirectedRequest(string $body = 'Misdirected Request', ?HttpHeader $header = null): self {
		return new self(421, $header, $body, '');
	}
	public static function UnSendableEntity(string $body = 'UnSendable Entity', ?HttpHeader $header = null): self {
		return new self(422, $header, $body, '');
	}
	public static function Locked(string $body = 'Locked', ?HttpHeader $header = null): self {
		return new self(423, $header, $body, '');
	}
	public static function FailedDependency(string $body = 'Failed Dependency', ?HttpHeader $header = null): self {
		return new self(424, $header, $body, '');
	}
	public static function TooEarly(string $body = 'Too Early', ?HttpHeader $header = null): self {
		return new self(425, $header, $body, '');
	}
	public static function UpgradeRequired(string $body = 'Upgrade Required', ?HttpHeader $header = null): self {
		return new self(426, $header, $body, '');
	}
	public static function PreconditionRequired(string $body = 'Precondition Required', ?HttpHeader $header = null): self {
		return new self(428, $header, $body, '');
	}
	public static function TooManyRequests(string $body = 'Too Many Requests', ?HttpHeader $header = null): self {
		return new self(429, $header, $body, '');
	}
	public static function RequestHeaderFieldsTooLarge(string $body = 'Request Header Fields Too Large', ?HttpHeader $header = null): self {
		return new self(431, $header, $body, '');
	}
	public static function UnavailableForLegalReasons(string $body = 'Unavailable For Legal Reasons', ?HttpHeader $header = null): self {
		return new self(451, $header, $body, '');
	}
	#endregion 4xx Client errors


	#region 5xx Server errors

	public static function InternalServerError(string $body = 'Internal Server Error', ?HttpHeader $header = null): self {
		return new self(500, $header, $body, '');
	}
	public static function NotImplemented(string $body = 'Not Implemented', ?HttpHeader $header = null): self {
		return new self(501, $header, $body, '');
	}
	public static function BadGateway(string $body = 'Bad Gateway', ?HttpHeader $header = null): self {
		return new self(502, $header, $body, '');
	}
	public static function ServiceUnavailable(string $body = 'Service Unavailable', ?HttpHeader $header = null): self {
		return new self(503, $header, $body, '');
	}
	public static function GatewayTimeout(string $body = 'Gateway Timeout', ?HttpHeader $header = null): self {
		return new self(504, $header, $body, '');
	}
	public static function HttpVersionNotSupported(string $body = 'HTTP Version Not Supported', ?HttpHeader $header = null): self {
		return new self(505, $header, $body, '');
	}
	public static function VariantAlsoNegotiates(string $body = 'Variant Also Negotiates', ?HttpHeader $header = null): self {
		return new self(506, $header, $body, '');
	}
	public static function InsufficientStorage(string $body = 'Insufficient Storage', ?HttpHeader $header = null): self {
		return new self(507, $header, $body, '');
	}
	public static function LoopDetected(string $body = 'Loop Detected', ?HttpHeader $header = null): self {
		return new self(508, $header, $body, '');
	}
	public static function BandwidthLimitExceeded(string $body = 'Bandwidth Limit Exceeded', ?HttpHeader $header = null): self {
		return new self(509, $header, $body, '');
	}
	public static function NotExtended(string $body = 'Not Extended', ?HttpHeader $header = null): self {
		return new self(510, $header, $body, '');
	}
	public static function NetworkAuthenticationRequired(string $body = 'Network Authentication Required', ?HttpHeader $header = null): self {
		return new self(511, $header, $body, '');
	}

	#endregion 5xx Server errors

	public function getStatusCode(): int { return $this->statusCode; }
	public function getHeaders(): HttpHeader { return $this->headers; }
	public function getBody(): string { return $this->body; }
	public function getUrl(): string { return $this->url; }

	public function isInformative(): bool
	{
		return $this->statusCode >= 100 && $this->statusCode < 200;
	}

	public function isSuccessful(): bool
	{
		return $this->statusCode >= 200 && $this->statusCode < 300;
	}

	public function isRedirection(): bool
	{
		return $this->statusCode >= 300 && $this->statusCode < 400;
	}

	public function isClientError(): bool
	{
		return $this->statusCode >= 400 && $this->statusCode < 500;
	}

	public function IsServerError(): bool
	{
		return $this->statusCode >= 500 && $this->statusCode < 600;
	}
}