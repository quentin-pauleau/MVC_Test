<?php
namespace Modules\Http;

use Modules\Http\HttpMethods;
use Modules\Http\HttpHeader;

/**
 * Simple HTTP client using cURL
 */
final class HttpClient
{
	private ?HttpHeader $defaultHeaders = null;
	private int $timeoutSeconds = 30;
	private bool $verifyTLS = true;

	public function __construct(?HttpHeader $defaultHeaders = null)
	{
		$this->defaultHeaders = $defaultHeaders;
	}

	public function withTimeout(int $seconds): self { $this->timeoutSeconds = max(0, $seconds); return $this; }
	public function withTLSVerification(bool $verify): self { $this->verifyTLS = $verify; return $this; }
	public function withDefaultHeaders(HttpHeader $headers): self { $this->defaultHeaders = $headers; return $this; }

	/**
	 * Core request method
	 * @param HttpMethods $method
	 * @param string $url
	 * @param null|string|array $body string for raw body, or array for form fields / json
	 * @param ?HttpHeader $headers additional headers to merge with defaults
	 */
	public function Request(HttpMethods $method, string $url, null|string|array $body = null, ?HttpHeader $headers = null): HttpResponse
	{
		$ch = curl_init();
		$allHeaders = new HttpHeader();

		if ($this->defaultHeaders) {
			$allHeaders->Replace($this->defaultHeaders->ToArray());
		}
		if ($headers) {
			foreach ($headers->ToArray() as $k => $values) {
				foreach ($values as $v) {
					$allHeaders->Add($k, $v);
				}
			}
		}

		$curlHeaders = [];
		foreach ($allHeaders->ToHeaderLines() as $line)
			$curlHeaders[] = $line;

		$opts = [
			CURLOPT_URL => $url,
			CURLOPT_CUSTOMREQUEST => $method->value,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER => true, // include headers in output so we can parse them
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_TIMEOUT => $this->timeoutSeconds,
			CURLOPT_HTTPHEADER => $curlHeaders,
		];

		if (!$this->verifyTLS) {
			$opts[CURLOPT_SSL_VERIFYHOST] = 0;
			$opts[CURLOPT_SSL_VERIFYPEER] = 0;
		}

		// Prepare body
		if ($body !== null) {
			if (is_array($body)) {
				// if Content-Type application/json, encode as json, else form
				$ct = $allHeaders->Get('Content-Type', '');
				if (stripos((string)$ct, 'application/json') !== false) {
					$opts[CURLOPT_POSTFIELDS] = json_encode($body);
				} else {
					$opts[CURLOPT_POSTFIELDS] = http_build_query($body);
				}
			} else {
				$opts[CURLOPT_POSTFIELDS] = $body;
			}
		}

		curl_setopt_array($ch, $opts);
		$raw = curl_exec($ch);
		if ($raw === false) {
			$error = curl_error($ch);
			curl_close($ch);
			throw new \RuntimeException('HTTP request failed: ' . $error);
		}

		$status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
		$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL) ?: $url;
		curl_close($ch);

		$rawHeaders = substr($raw, 0, $headerSize);
		$bodyStr = substr($raw, $headerSize);

		// Handle multiple header blocks (redirects). Take the last block.
		$blocks = preg_split("/(?:\r?\n){2}/", trim($rawHeaders));
		$lastBlock = $blocks[count($blocks)-1] ?? '';
		$lines = preg_split("/\r?\n/", (string)$lastBlock) ?: [];

		$statusLine = array_shift($lines) ?: '';
		$reason = '';
		if (preg_match('#^HTTP/\d+\.\d+\s+(\d{3})(?:\s+(.*))?$#i', $statusLine, $m)) {
			$status = (int)$m[1];
			$reason = $m[2] ?? '';
		}

		$respHeaders = HttpHeader::FromHeaderLines($lines);

		return new HttpResponse($status, $reason, $respHeaders, (string)$bodyStr, (string)$finalUrl);
	}

	// Convenience methods
	public function Get(string $url, ?HttpHeader $headers = null): HttpResponse
	{
		return $this->Request(HttpMethods::GET, $url, null, $headers);
	}

	public function Post(string $url, null|string|array $body = null, ?HttpHeader $headers = null): HttpResponse
	{
		return $this->Request(HttpMethods::POST, $url, $body, $headers);
	}

	public function Put(string $url, null|string|array $body = null, ?HttpHeader $headers = null): HttpResponse
	{
		return $this->Request(HttpMethods::PUT, $url, $body, $headers);
	}

	public function Patch(string $url, null|string|array $body = null, ?HttpHeader $headers = null): HttpResponse
	{
		return $this->Request(HttpMethods::PATCH, $url, $body, $headers);
	}

	public function Delete(string $url, null|string|array $body = null, ?HttpHeader $headers = null): HttpResponse
	{
		return $this->Request(HttpMethods::DELETE, $url, $body, $headers);
	}
}