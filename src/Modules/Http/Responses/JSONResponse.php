<?php
namespace Modules\Http\Responses;

use Modules\Http\HttpHeader;
use Modules\Serialisation\Json;

use function PHPSTORM_META\map;

/**
 * Display json data
 */
class JsonResponse extends Response
{
	public function __construct(
		protected object|array $body, 
		protected ?HttpHeader $headers = null, 
		protected int $statusCode = 200
	) {}

	public function Send(): void
	{
		$this->headers ??= HttpHeader::Create();

		$this->headers->Json()->Send(true, $this->statusCode);
		
		if (!($this->body instanceof Json))
			$this->body = Json::Serialize($this->body);
		
		echo $this->body;
		exit;
	}

	public static function Data(
		object|array $data, 
		?HttpHeader $header = null,
		int $statusCode = 200
	): self {
		return new self(
			[
				'status' => 'success',
				'data' => $data,
				'errors' => null
			], 
			$header, 
			$statusCode
		);
	}

	public static function Errors(
		string|array $errors, 
		?HttpHeader $header = null,
		int $statusCode = 500
	): self {
		return new self(
			[
				'status' => 'error',
				'data' => null,
				'errors' => $errors
			], 
			$header, 
			$statusCode
		);
	}

	#region 2xx Success
	
	public static function Ok(object|array $data, ?HttpHeader $header = null): self {
		return self::Data($data, $header, 200);
	}
	public static function Created(object|array $data, ?HttpHeader $header = null): self {
		return self::Data($data, $header, 201);
	}
	public static function Accepted(object|array $data, ?HttpHeader $header = null): self {
		return self::Data($data, $header, '202');
	}
	public static function NoContent(?HttpHeader $header = null): self {
		return self::Data([], $header, 204);
	}

	#endregion 2xx Success
	

	#region 4xx Client errors
	
	public static function BadRequest(string|array $error = 'Bad Request', ?HttpHeader $header = null): self {
		return self::Errors($error, $header, 400);
	}
	public static function Unauthorized(string|array $error = 'Unauthorized', ?HttpHeader $header = null): self {
		return self::Errors($error, $header, 401);
	}
	public static function Forbidden(string|array $error = 'Forbidden', ?HttpHeader $header = null): self {
		return self::Errors($error, $header, 402);
	}
	public static function NotFound(string|array $error = 'Not Found', ?HttpHeader $header = null): self {
		return self::Errors($error, $header, 404);
	}
	
	#endregion 4xx Client errors


	#region 5xx Server errors

	public static function InternalServerError(string|array $error = 'Internal Server Error', ?HttpHeader $header = null): self {
		return self::Errors($error, $header, 500);
	}
	public static function NotImplemented(string|array $error = 'Not Implemented', ?HttpHeader $header = null): self {
		return self::Errors($error, $header, 501);
	}
	public static function BadGateway(string|array $error = 'Bad Gateway', ?HttpHeader $header = null): self {
		return self::Errors($error, $header, 502);
	}

	#endregion 5xx Server errors
}