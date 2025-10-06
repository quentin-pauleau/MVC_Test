<?php
namespace Modules\Http;

use Countable;
use DateTimeInterface;
use IteratorAggregate;
use Traversable;

/**
 * Represents a collection of HTTP headers (case-insensitive keys).
 * - Can be populated from arrays, header lines, or globals.
 * - Can be used to send headers in a response.
 * - Now includes a fluent builder-style API for common headers.
 */
final class HttpHeader implements IteratorAggregate, Countable
{
	/**
	 * Internal storage for headers (normalized name => list of values)
	 * @var array<string, string[]>
	 */
	private array $headers = [];

	/**
	 * @param array<string, string|string[]> $initial
	 */
	public function __construct(array $initial = [])
	{
		if ($initial !== [])
			$this->Replace($initial);
	}

	/** Static builder entry */
	public static function Create(array $initial = []): self
	{
		return new self($initial);
	}

	/**
	 * Normalize header name to Title-Case with dashes (case-insensitive handling).
	 */
	private function normalizeName(string $name): string
	{
		$name = trim($name);
		$name = str_replace('_', '-', $name);
		$name = strtolower($name);
		return preg_replace_callback(
			'/(^|-)[a-z]/',
			fn ($m) => strtoupper($m[0]),
			$name
		) ?? $name;
	}

	/**
	 * Replace all values for a header.
	 * @param string $name
	 * @param string|string[] $value
	 */
	public function Set(string $name, string|array $value): void
	{
		$n = $this->normalizeName($name);
		$values = is_array($value) ? $value : [$value];
		$this->headers[$n] = array_map('strval', array_values($values));
	}

	/** Fluent alias of Set() that returns $this */
	public function With(string $name, string|array $value): self
	{
		$this->Set($name, $value);
		return $this;
	}

	/**
	 * Add a single value to a header (does not replace existing ones).
	 */
	public function Add(string $name, string $value): void
	{
		$n = $this->normalizeName($name);
		$this->headers[$n] ??= [];
		$this->headers[$n][] = (string)$value;
	}

	/** Fluent alias of Add() that returns $this */
	public function Plus(string $name, string $value): self
	{
		$this->Add($name, $value);
		return $this;
	}

	/**
	 * Get a header value as a single comma-separated string (RFC7230 style).
	 * Returns $default if header is not present.
	 */
	public function Get(string $name, ?string $default = null): ?string
	{
		$n = $this->normalizeName($name);
		if (!isset($this->headers[$n]) || $this->headers[$n] === []) {
			return $default;
		}
		return implode(', ', $this->headers[$n]);
	}

	/**
	 * Get all values for a given header (as an array).
	 * @return string[]
	 */
	public function GetAll(string $name): array
	{
		$n = $this->normalizeName($name);
		return $this->headers[$n] ?? [];
	}

	/** Check if a header exists (case-insensitive). */
	public function Has(string $name): bool
	{
		$n = $this->normalizeName($name);
		return array_key_exists($n, $this->headers);
	}

	/** Remove a header completely. */
	public function Remove(string $name): void
	{
		$n = $this->normalizeName($name);
		unset($this->headers[$n]);
	}

	/** Remove all headers. */
	public function Clear(): void
	{
		$this->headers = [];
	}

	/**
	 * Replace the entire set of headers with the provided array.
	 * @param array<string, string|string[]> $headers
	 */
	public function Replace(array $headers): void
	{
		$this->headers = [];
		foreach ($headers as $k => $v)
			$this->Set((string)$k, $v);
	}

	/**
	 * Export headers as an associative array of name => list of values.
	 * @return array<string, string[]>
	 */
	public function ToArray(): array
	{
		return $this->headers;
	}

	/**
	 * Export headers as an array of "Name: value" lines.
	 * @return string[]
	 */
	public function ToHeaderLines(): array
	{
		$lines = [];
		foreach ($this->headers as $name => $values) {
			if ($values === []) {
				$lines[] = $name . ':';
			} else {
				foreach ($values as $v) {
					$lines[] = $name . ': ' . $v;
				}
			}
		}
		return $lines;
	}

	/**
	 * Send the headers to the client using PHP's header() function.
	 * If $responseCode is provided, it is applied with the first header sent.
	 */
	public function Send(bool $replace = true, ?int $responseCode = null): void
	{
		http_response_code($responseCode ?? 200);

		foreach ($this->headers as $name => $values) {
			if ($values === [])
				header("{$name}:", $replace);
			else {
				$replace2 = $replace;
				foreach ($values as $value) {
					header("{$name}:{$value}", $replace2);
					$replace2 = false;
				}
			}
		}
	}

	/* ================================ */
	/*   Common headers - Builder API   */
	/* ================================ */

	public function ContentType(string $mime, ?string $charset = null): self
	{
		$value = $mime;
		if ($charset !== null && $charset !== '')
			$value .= "; charset={$charset};";
		
		return $this->With('Content-Type', $value);
	}

	public function Json(): self { return $this->ContentType('application/json', 'utf-8'); }
	public function Html(?string $charset = 'utf-8'): self { return $this->ContentType('text/html', $charset); }
	public function Text(?string $charset = 'utf-8'): self { return $this->ContentType('text/plain', $charset); }
	public function Xml(?string $charset = 'utf-8'): self { return $this->ContentType('application/xml', $charset); }

	public function ContentLength(int $bytes): self
	{
		return $this->With('Content-Length', (string)$bytes);
	}

	public function ContentDispositionAttachment(string $filename, ?string $filenameStar = null): self
	{
		return $this->contentDisposition('attachment', $filename, $filenameStar);
	}

	public function ContentDispositionInline(?string $filename = null, ?string $filenameStar = null): self
	{
		return $this->contentDisposition('inline', $filename, $filenameStar);
	}

	private function contentDisposition(string $type, ?string $filename, ?string $filenameStar): self
	{
		$parts = [$type];
		if ($filename !== null && $filename !== '') {
			$quoted = '"' . str_replace('"', '\\"', $filename) . '"';
			$parts[] = "filename=$quoted";
		}
		if ($filenameStar !== null && $filenameStar !== '') {
			$enc = rawurlencode($filenameStar);
			$parts[] = "filename*=UTF-8''{$enc}";
		}
		return $this->With('Content-Disposition', implode('; ', $parts));
	}

	public function Location(string $url): self
	{
		return $this->With('Location', $url);
	}

	public function ETag(string $tag, bool $weak = false): self
	{
		$tag = '"' . trim($tag, '"') . '"';
		return $this->With('ETag', ($weak ? 'W/' : '') . $tag);
	}

	public function LastModified(DateTimeInterface $date): self
	{
		return $this->With('Last-Modified', gmdate('D, d M Y H:i:s', $date->getTimestamp()) . ' GMT');
	}

	public function Vary(string|array $fields): self
	{
		$existing = $this->GetAll('Vary');
		$current = [];
		foreach ($existing as $line) {
			$current = array_merge($current, array_map('trim', explode(',', $line)));
		}
		$add = is_array($fields) ? $fields : [$fields];
		$merged = array_values(array_unique(array_filter(array_map('trim', array_merge($current, $add)))));
		return $this->With('Vary', implode(', ', $merged));
	}

	/* Caching */
	public function NoCache(): self
	{
		$this->With('Cache-Control', 'no-cache, no-store, must-revalidate');
		$this->With('Pragma', 'no-cache');
		$this->With('Expires', '0');
		return $this;
	}

	public function CachePublic(int $maxAge): self
	{
		return $this->With('Cache-Control', 'public, max-age=' . max(0, $maxAge));
	}

	public function CachePrivate(int $maxAge): self
	{
		return $this->With('Cache-Control', 'private, max-age=' . max(0, $maxAge));
	}

	/* CORS */
	public function CORSAllowOrigin(string $origin): self
	{
		return $this->With('Access-Control-Allow-Origin', $origin);
	}

	public function CORSAllowAll(): self
	{
		return $this->With('Access-Control-Allow-Origin', '*');
	}

	public function CORSPreflight(string|array $methods = ['GET','POST','OPTIONS'], string|array $headers = ['Content-Type','Authorization'], int $maxAge = 86400, bool $credentials = false): self
	{
		$methodsStr = is_array($methods) ? implode(', ', array_map('strtoupper', $methods)) : strtoupper($methods);
		$headersStr = is_array($headers) ? implode(', ', array_map('trim', $headers)) : $headers;
		$this->With('Access-Control-Allow-Methods', $methodsStr);
		$this->With('Access-Control-Allow-Headers', $headersStr);
		$this->With('Access-Control-Max-Age', (string)max(0, $maxAge));
		if ($credentials) $this->With('Access-Control-Allow-Credentials', 'true');
		return $this;
	}

	public function CORSExposeHeaders(string|array $headers): self
	{
		$headersStr = is_array($headers) ? implode(', ', array_map('trim', $headers)) : $headers;
		return $this->With('Access-Control-Expose-Headers', $headersStr);
	}

	/* Security */
	public function HSTS(int $maxAge = 31536000, bool $includeSubdomains = true, bool $preload = false): self
	{
		$parts = ['max-age=' . max(0, $maxAge)];
		if ($includeSubdomains) $parts[] = 'includeSubDomains';
		if ($preload) $parts[] = 'preload';
		return $this->With('Strict-Transport-Security', implode('; ', $parts));
	}

	public function FrameDeny(): self { return $this->With('X-Frame-Options', 'DENY'); }
	public function FrameSameOrigin(): self { return $this->With('X-Frame-Options', 'SAMEORIGIN'); }
	public function XContentTypeOptionsNosniff(): self { return $this->With('X-Content-Type-Options', 'nosniff'); }
	public function ReferrerPolicy(string $policy): self { return $this->With('Referrer-Policy', $policy); }
	public function PermissionsPolicy(string $policy): self { return $this->With('Permissions-Policy', $policy); }
	public function CSP(string $policy): self { return $this->With('Content-Security-Policy', $policy); }

	/* Authorization */
	public function AuthorizationBearer(string $token): self
	{
		return $this->With('Authorization', 'Bearer ' . $token);
	}

	public function WWWAuthenticate(string $challenge): self
	{
		return $this->With('WWW-Authenticate', $challenge);
	}

	/* Cookies */
	public function Cookie(string $name, string $value, array $options = []): self
	{
		$cookie = rawurlencode($name) . '=' . rawurlencode($value);

		if (isset($options['expires'])) {
			$expires = $options['expires'];
			if ($expires instanceof DateTimeInterface) {
				$cookie .= '; Expires=' . gmdate('D, d M Y H:i:s', $expires->getTimestamp()) . ' GMT';
			} elseif (is_int($expires)) {
				$cookie .= '; Expires=' . gmdate('D, d M Y H:i:s', $expires) . ' GMT';
			} elseif (is_string($expires) && $expires !== '') {
				$cookie .= '; Expires=' . $expires;
			}
		}
		if (isset($options['max_age'])) $cookie .= '; Max-Age=' . (int)$options['max_age'];
		if (!empty($options['domain'])) $cookie .= '; Domain=' . $options['domain'];
		$cookie .= '; Path=' . ($options['path'] ?? '/');
		if (!empty($options['secure'])) $cookie .= '; Secure';
		if (!empty($options['httponly'])) $cookie .= '; HttpOnly';
		if (isset($options['samesite'])) {
			$ss = ucfirst(strtolower((string)$options['samesite']));
			if (in_array($ss, ['Lax','Strict','None'], true)) {
				$cookie .= '; SameSite=' . $ss;
			}
		}

		return $this->Plus('Set-Cookie', $cookie);
	}

	public function DeleteCookie(string $name, array $options = []): self
	{
		$options['expires'] = 0; // Thu, 01 Jan 1970 ...
		$options['max_age'] = 0;
		$options['path'] = $options['path'] ?? '/';
		return $this->Cookie($name, '', $options);
	}

	/* Accept headers (useful when building clients) */
	public function Accept(string|array $types): self
	{
		$value = is_array($types) ? implode(', ', $types) : $types;
		return $this->With('Accept', $value);
	}

	public function AcceptLanguage(string|array $langs): self
	{
		$value = is_array($langs) ? implode(', ', $langs) : $langs;
		return $this->With('Accept-Language', $value);
	}

	/* ===================== */
	/*   Static factories    */
	/* ===================== */

	/**
	 * Build from an array of raw header lines (e.g. ["Content-Type: text/html"]).
	 * @param string[] $lines
	 */
	public static function FromHeaderLines(array $lines): self
	{
		$h = new self();
		foreach ($lines as $line) {
			$line = trim($line);
			if ($line === '' || !str_contains($line, ':')) {
				continue;
			}
			[$name, $value] = explode(':', $line, 2);
			$h->Add($name, ltrim($value));
		}
		return $h;
	}

	/**
	 * Build from current request globals (request headers).
	 * Uses getallheaders() when available, otherwise falls back to $_SERVER.
	 */
	public static function FromGlobalsRequest(): self
	{
		$h = new self();
		if (function_exists('getallheaders')) {
			foreach (getallheaders() as $name => $value) {
				$h->Set((string)$name, (string)$value);
			}
		} else {
			foreach ($_SERVER as $key => $value) {
				if (str_starts_with($key, 'HTTP_')) {
					$headerName = substr($key, 5); // remove HTTP_
					$headerName = str_replace('_', '-', $headerName);
					$h->Set($headerName, (string)$value);
				}
			}
			// Some headers are not prefixed with HTTP_
			if (isset($_SERVER['CONTENT_TYPE'])) {
				$h->Set('Content-Type', (string)$_SERVER['CONTENT_TYPE']);
			}
			if (isset($_SERVER['CONTENT_LENGTH'])) {
				$h->Set('Content-Length', (string)$_SERVER['CONTENT_LENGTH']);
			}
		}
		return $h;
	}

	/**
	 * Build from headers that are currently scheduled/emitted by PHP (response headers).
	 * Uses headers_list().
	 */
	public static function FromEmittedResponse(): self
	{
		$h = new self();
		foreach (headers_list() as $line) {
			$line = trim($line);
			if ($line === '' || !str_contains($line, ':')) {
				continue;
			}
			[$name, $value] = explode(':', $line, 2);
			$h->Add($name, ltrim($value));
		}
		return $h;
	}

	/** @inheritDoc */
	public function getIterator(): Traversable
	{
		yield from $this->headers;
	}

	/** @inheritDoc */
	public function count(): int
	{
		return count($this->headers);
	}
}