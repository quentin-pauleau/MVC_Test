<?php
namespace Core\Responses;

use Modules\Http\HttpHeader;

/**
 * Display json data
 */
class JSONResponse extends Response
{
    protected $data;
    protected ?HttpHeader $headers;
    protected int $statusCode;

    public function __construct($data, ?HttpHeader $headers = null, int $statusCode = 200)
    {
        $this->data = $data;
        $this->headers = $headers;
        $this->statusCode = $statusCode;
    }

    public function Process(): void
    {
        $hdr = $this->headers ?? HttpHeader::Create()->Json();
        if (!$this->headers) {
            $hdr->Json();
        }
        $hdr->Send(true, $this->statusCode);
        echo json_encode($this->data);
        exit;
    }
}