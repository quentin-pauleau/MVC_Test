<?php
namespace Modules\Http\Responses;

use Modules\Http\HttpHeader;
use Modules\Serialisation\Json;

/**
 * Display json data
 */
class JsonResponse extends Response
{
	public function __construct(
		protected array|object $data, 
		protected ?HttpHeader $headers = null, 
		protected int $statusCode = 200
	) {}

	public function Send(): void
	{
		$header = $this->headers ?? HttpHeader::Create()->Json();

		if (!$this->headers)
			$header->Json();

		$header->Send(true, $this->statusCode);
		
		if (!($this->data instanceof Json))
			$this->data = Json::Serialize($this->data);
		
		echo $this->data;
		exit;
	}
}