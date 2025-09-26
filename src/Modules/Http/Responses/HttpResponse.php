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
	 * @param string $reasonPhrase
	 * @param HttpHeader $headers
	 * @param string $body
	 * @param string $url
	 */
	public function __construct(
		private int $statusCode,
		private string $reasonPhrase,
		private HttpHeader $headers,
		private string $body,
		private string $url,
	) {}


	public function Send(): void {

		if ($this->statusCode === 301 || $this->statusCode === 302)
			$this->headers->Location($this->url);

		$this->headers->Send();

		http_response_code($this->statusCode);

		echo $this->body;
		exit;
	}




	#region 1xx Informational
	public static function Continue_(string $body = '', ?HttpHeader $header = null): self { // avoid PHP reserved word
		return new self(100, 'Continue', $header ?? new HttpHeader(), $body, '');
	}
	public static function SwitchingProtocols(string $body = '', ?HttpHeader $header = null): self {
		return new self(101, 'Switching Protocols', $header ?? new HttpHeader(), $body, '');
	}
	public static function Sending(string $body = '', ?HttpHeader $header = null): self {
		return new self(102, 'Sending', $header ?? new HttpHeader(), $body, '');
	}
	public static function EarlyHints(string $body = '', ?HttpHeader $header = null): self {
		return new self(103, 'Early Hints', $header ?? new HttpHeader(), $body, '');
	}
	#endregion 1xx Informational


	#region 2xx Success
	
	public static function Ok(string $body = '', ?HttpHeader $header = null): self {
		return new self(200, 'OK', $header ?? new HttpHeader(), $body, '');
	}
	public static function Created(string $body = '', ?HttpHeader $header = null): self {
		return new self(201, 'Created', $header ?? new HttpHeader(), $body, '');
	}
	public static function Accepted(string $body = '', ?HttpHeader $header = null): self {
		return new self(202, 'Accepted', $header ?? new HttpHeader(), $body, '');
	}
	public static function NonAuthoritativeInformation(string $body = '', ?HttpHeader $header = null): self {
		return new self(203, 'Non-Authoritative Information', $header ?? new HttpHeader(), $body, '');
	}
	public static function NoContent(string $body = '', ?HttpHeader $header = null): self {
		return new self(204, 'No Content', $header ?? new HttpHeader(), $body, '');
	}
	public static function ResetContent(string $body = '', ?HttpHeader $header = null): self {
		return new self(205, 'Reset Content', $header ?? new HttpHeader(), $body, '');
	}
	public static function PartialContent(string $body = '', ?HttpHeader $header = null): self {
		return new self(206, 'Partial Content', $header ?? new HttpHeader(), $body, '');
	}
	public static function MultiStatus(string $body = '', ?HttpHeader $header = null): self {
		return new self(207, 'Multi-Status', $header ?? new HttpHeader(), $body, '');
	}
	public static function AlreadyReported(string $body = '', ?HttpHeader $header = null): self {
		return new self(208, 'Already Reported', $header ?? new HttpHeader(), $body, '');
	}
	public static function ImUsed(string $body = '', ?HttpHeader $header = null): self {
		return new self(226, 'IM Used', $header ?? new HttpHeader(), $body, '');
	}
	#endregion 2xx Success


	#region 3xx Redirection
	public static function MultipleChoices(string $body = '', ?HttpHeader $header = null): self {
		return new self(300, 'Multiple Choices', $header ?? new HttpHeader(), $body, '');
	}
	public static function MovedPermanently(string $body = '', ?HttpHeader $header = null): self {
		return new self(301, 'Moved Permanently', $header ?? new HttpHeader(), $body, '');
	}
	public static function Found(string $body = '', ?HttpHeader $header = null): self {
		return new self(302, 'Found', $header ?? new HttpHeader(), $body, '');
	}
	public static function SeeOther(string $body = '', ?HttpHeader $header = null): self {
		return new self(303, 'See Other', $header ?? new HttpHeader(), $body, '');
	}
	public static function NotModified(string $body = '', ?HttpHeader $header = null): self {
		return new self(304, 'Not Modified', $header ?? new HttpHeader(), $body, '');
	}
	public static function UseProxy(string $body = '', ?HttpHeader $header = null): self {
		return new self(305, 'Use Proxy', $header ?? new HttpHeader(), $body, '');
	}
	public static function Unused(string $body = '', ?HttpHeader $header = null): self {
		return new self(306, 'Unused', $header ?? new HttpHeader(), $body, '');
	}
	public static function TemporaryRedirect(string $body = '', ?HttpHeader $header = null): self {
		return new self(307, 'Temporary Redirect', $header ?? new HttpHeader(), $body, '');
	}
	public static function PermanentRedirect(string $body = '', ?HttpHeader $header = null): self {
		return new self(308, 'Permanent Redirect', $header ?? new HttpHeader(), $body, '');
	}

	#endregion 3xx Redirection


	#region 4xx Client errors
	public static function BadRequest(string $body = '', ?HttpHeader $header = null): self {
		return new self(400, 'Bad Request', $header ?? new HttpHeader(), $body, '');
	}
	public static function Unauthorized(string $body = '', ?HttpHeader $header = null): self {
		return new self(401, 'Unauthorized', $header ?? new HttpHeader(), $body, '');
	}
	public static function PaymentRequired(string $body = '', ?HttpHeader $header = null): self {
		return new self(402, 'Payment Required', $header ?? new HttpHeader(), $body, '');
	}
	public static function Forbidden(string $body = '', ?HttpHeader $header = null): self {
		return new self(403, 'Forbidden', $header ?? new HttpHeader(), $body, '');
	}
	public static function NotFound(string $body = '', ?HttpHeader $header = null): self {
		return new self(404, 'Not Found', $header ?? new HttpHeader(), $body, '');
	}
	public static function MethodNotAllowed(string $body = '', ?HttpHeader $header = null): self {
		return new self(405, 'Method Not Allowed', $header ?? new HttpHeader(), $body, '');
	}
	public static function NotAcceptable(string $body = '', ?HttpHeader $header = null): self {
		return new self(406, 'Not Acceptable', $header ?? new HttpHeader(), $body, '');
	}
	public static function ProxyAuthenticationRequired(string $body = '', ?HttpHeader $header = null): self {
		return new self(407, 'Proxy Authentication Required', $header ?? new HttpHeader(), $body, '');
	}
	public static function RequestTimeout(string $body = '', ?HttpHeader $header = null): self {
		return new self(408, 'Request Timeout', $header ?? new HttpHeader(), $body, '');
	}
	public static function Conflict(string $body = '', ?HttpHeader $header = null): self {
		return new self(409, 'Conflict', $header ?? new HttpHeader(), $body, '');
	}
	public static function Gone(string $body = '', ?HttpHeader $header = null): self {
		return new self(410, 'Gone', $header ?? new HttpHeader(), $body, '');
	}
	public static function LengthRequired(string $body = '', ?HttpHeader $header = null): self {
		return new self(411, 'Length Required', $header ?? new HttpHeader(), $body, '');
	}
	public static function PreconditionFailed(string $body = '', ?HttpHeader $header = null): self {
		return new self(412, 'Precondition Failed', $header ?? new HttpHeader(), $body, '');
	}
	public static function PayloadTooLarge(string $body = '', ?HttpHeader $header = null): self {
		return new self(413, 'Payload Too Large', $header ?? new HttpHeader(), $body, '');
	}
	public static function UriTooLong(string $body = '', ?HttpHeader $header = null): self {
		return new self(414, 'URI Too Long', $header ?? new HttpHeader(), $body, '');
	}
	public static function UnsupportedMediaType(string $body = '', ?HttpHeader $header = null): self {
		return new self(415, 'Unsupported Media Type', $header ?? new HttpHeader(), $body, '');
	}
	public static function RangeNotSatisfiable(string $body = '', ?HttpHeader $header = null): self {
		return new self(416, 'Range Not Satisfiable', $header ?? new HttpHeader(), $body, '');
	}
	public static function ExpectationFailed(string $body = '', ?HttpHeader $header = null): self {
		return new self(417, 'Expectation Failed', $header ?? new HttpHeader(), $body, '');
	}
	public static function ImATeapot(string $body = '', ?HttpHeader $header = null): self {
		return new self(418, "I'm a teapot", $header ?? new HttpHeader(), $body, '');
	}
	public static function MisdirectedRequest(string $body = '', ?HttpHeader $header = null): self {
		return new self(421, 'Misdirected Request', $header ?? new HttpHeader(), $body, '');
	}
	public static function UnSendableEntity(string $body = '', ?HttpHeader $header = null): self {
		return new self(422, 'UnSendable Entity', $header ?? new HttpHeader(), $body, '');
	}
	public static function Locked(string $body = '', ?HttpHeader $header = null): self {
		return new self(423, 'Locked', $header ?? new HttpHeader(), $body, '');
	}
	public static function FailedDependency(string $body = '', ?HttpHeader $header = null): self {
		return new self(424, 'Failed Dependency', $header ?? new HttpHeader(), $body, '');
	}
	public static function TooEarly(string $body = '', ?HttpHeader $header = null): self {
		return new self(425, 'Too Early', $header ?? new HttpHeader(), $body, '');
	}
	public static function UpgradeRequired(string $body = '', ?HttpHeader $header = null): self {
		return new self(426, 'Upgrade Required', $header ?? new HttpHeader(), $body, '');
	}
	public static function PreconditionRequired(string $body = '', ?HttpHeader $header = null): self {
		return new self(428, 'Precondition Required', $header ?? new HttpHeader(), $body, '');
	}
	public static function TooManyRequests(string $body = '', ?HttpHeader $header = null): self {
		return new self(429, 'Too Many Requests', $header ?? new HttpHeader(), $body, '');
	}
	public static function RequestHeaderFieldsTooLarge(string $body = '', ?HttpHeader $header = null): self {
		return new self(431, 'Request Header Fields Too Large', $header ?? new HttpHeader(), $body, '');
	}
	public static function UnavailableForLegalReasons(string $body = '', ?HttpHeader $header = null): self {
		return new self(451, 'Unavailable For Legal Reasons', $header ?? new HttpHeader(), $body, '');
	}
	#endregion 4xx Client errors


	#region 5xx Server errors

	public static function InternalServerError(string $body = '', ?HttpHeader $header = null): self {
		return new self(500, 'Internal Server Error', $header ?? new HttpHeader(), $body, '');
	}
	public static function NotImplemented(string $body = '', ?HttpHeader $header = null): self {
		return new self(501, 'Not Implemented', $header ?? new HttpHeader(), $body, '');
	}
	public static function BadGateway(string $body = '', ?HttpHeader $header = null): self {
		return new self(502, 'Bad Gateway', $header ?? new HttpHeader(), $body, '');
	}
	public static function ServiceUnavailable(string $body = '', ?HttpHeader $header = null): self {
		return new self(503, 'Service Unavailable', $header ?? new HttpHeader(), $body, '');
	}
	public static function GatewayTimeout(string $body = '', ?HttpHeader $header = null): self {
		return new self(504, 'Gateway Timeout', $header ?? new HttpHeader(), $body, '');
	}
	public static function HttpVersionNotSupported(string $body = '', ?HttpHeader $header = null): self {
		return new self(505, 'HTTP Version Not Supported', $header ?? new HttpHeader(), $body, '');
	}
	public static function VariantAlsoNegotiates(string $body = '', ?HttpHeader $header = null): self {
		return new self(506, 'Variant Also Negotiates', $header ?? new HttpHeader(), $body, '');
	}
	public static function InsufficientStorage(string $body = '', ?HttpHeader $header = null): self {
		return new self(507, 'Insufficient Storage', $header ?? new HttpHeader(), $body, '');
	}
	public static function LoopDetected(string $body = '', ?HttpHeader $header = null): self {
		return new self(508, 'Loop Detected', $header ?? new HttpHeader(), $body, '');
	}
	public static function BandwidthLimitExceeded(string $body = '', ?HttpHeader $header = null): self {
		return new self(509, 'Bandwidth Limit Exceeded', $header ?? new HttpHeader(), $body, '');
	}
	public static function NotExtended(string $body = '', ?HttpHeader $header = null): self {
		return new self(510, 'Not Extended', $header ?? new HttpHeader(), $body, '');
	}
	public static function NetworkAuthenticationRequired(string $body = '', ?HttpHeader $header = null): self {
		return new self(511, 'Network Authentication Required', $header ?? new HttpHeader(), $body, '');
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