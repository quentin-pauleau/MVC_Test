<?php
namespace Core\Responses;

use Modules\Http\HttpHeader;

class URIRedirectionResponse extends RedirectionResponse
{
    /**
     * @param string $uri the uri the user should be redirected to
     */
    public function __construct(string $uri, int $statusCode = 302, ?HttpHeader $headers = null) {
        parent::__construct($uri, $statusCode, $headers);
    }
}