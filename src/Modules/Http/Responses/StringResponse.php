<?php
namespace Core\Responses;

use Modules\Http\HttpHeader;

/**
 * Display a text from a string
 * 
 * the text is display as html if {@see self::is_html} is set to true
 */
class StringResponse extends Response
{
    private string $text = '';
    private bool $is_html = true;
    private ?HttpHeader $headers = null;
    private int $statusCode = 200;

    public function __construct(string $text, bool $is_html = true, ?HttpHeader $headers = null, int $statusCode = 200)
    {
        $this->text = $text;
        $this->is_html = $is_html;
        $this->headers = $headers;
        $this->statusCode = $statusCode;
    }

    /**
     * Unset all variables and display the template with all
     * @return never
     */
    public function Process(): void {
        $hdr = $this->headers ?? HttpHeader::Create();
        if ($this->is_html) {
            $hdr->Html();
            $out = htmlspecialchars($this->text);
        } else {
            $hdr->Text();
            $out = $this->text;
        }
        $hdr->Send(true, $this->statusCode);
        echo $out;
        exit;
    }
}