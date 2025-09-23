<?php
namespace Modules\DotEnv;

use Exception;

/**
 * Lightweight .env loader and accessor.
 * - Parses KEY=VALUE pairs from a .env file
 * - Supports quoted values, comments, and variable expansion ${VAR}
 * - Can export values to PHP env (putenv, $_ENV, $_SERVER)
 */
final class DotEnv
{
	/**
	 * In-memory store of loaded env variables (normalized as strings)
	 * @var array<string,string>
	 */
	private array $env = [];

	/**
	 * Keep track of which file was loaded (for debugging)
	 */
	private ?string $loadedFile = null;

	/**
	 * Optionally construct and immediately load from a path or directory.
	 * If a directory is provided, it will attempt to load ".env" inside it.
	 * If null, it tries to load the project-root .env (3 levels up from this file).
	 */
	public function __construct(?string $path = null, bool $exportToPhpEnv = true)
	{
		if ($path !== null || ($path = $this->defaultDotEnvPath()) !== null) {
			// Ignore failures silently in constructor; explicit load() can throw
			try
			{
				$this->load($path, $exportToPhpEnv);
			} catch (Exception) {}
		}
	}

	/**
	 * Load variables from a .env file.
	 * @param string|null $path Path to file or directory. If directory, ".env" is appended.
	 * @param bool $exportToPhpEnv When true, propagate to putenv/$_ENV/$_SERVER
	 * @return int Number of variables loaded
	 * @throws Exception When the file cannot be read
	 */
	public function load(?string $path = null, bool $exportToPhpEnv = true): int
	{
		$file = $this->resolvePath($path);
		if ($file === null || !is_file($file) || !is_readable($file))
			throw new Exception("DotEnv: Unable to read file: ".($path ?? 'null'));

		$this->loadedFile = $file;
		$content = file($file, FILE_IGNORE_NEW_LINES);

		if ($content === false)
			throw new Exception("DotEnv: Failed to read lines from $file");

		$count = 0;
		foreach ($content as $lineNumber => $line) {
			$parsed = $this->parseLine($line);

			if ($parsed === null)
				continue; // blank/comment/invalid

			[$key, $value] = $parsed;
			$value = $this->expandVariables($value);

			$this->env[$key] = $value;
			if ($exportToPhpEnv)
				$this->export($key, $value);

			$count++;
		}
		return $count;
	}

	/**
	 * Get a variable from loaded env, falling back to PHP environment.
	 */
	public function get(string $key, ?string $default = null): ?string
	{
		if (array_key_exists($key, $this->env)) return $this->env[$key];
		$sys = self::getFromPhpEnv($key);
		return $sys !== null ? $sys : $default;
	}

	/** Return true if key exists either in loaded or PHP environment. */
	public function has(string $key): bool
	{
		return array_key_exists($key, $this->env) || self::getFromPhpEnv($key) !== null;
	}

	/** Set a variable in memory and optionally export to PHP environment. */
	public function set(string $key, string $value, bool $exportToPhpEnv = false): void
	{
		$this->env[$key] = $value;
		if ($exportToPhpEnv) $this->export($key, $value);
	}

	/** Return all currently loaded variables (does not include system env). */
	public function all(): array
	{
		return $this->env;
	}

	/** The file that was last loaded, if any. */
	public function getLoadedFile(): ?string
	{
		return $this->loadedFile;
	}

	// ---------- Internals ----------

	private function resolvePath(?string $path): ?string
	{
		// If a directory is given, look for ".env" inside
		if ($path !== null) {
			$candidate = is_dir($path) ? rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '.env' : $path;
			$real = realpath($candidate);
			return $real !== false ? $real : null;
		}
		// Default to project root .env (3 levels up from this file)
		return $this->defaultDotEnvPath();
	}

	private function defaultDotEnvPath(): ?string
	{
		$root = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..');

		if ($root === false)
			return null;

		$file = $root . DIRECTORY_SEPARATOR . '.env';
		$real = realpath($file);
		return $real !== false ? $real : (is_file($file) ? $file : null);
	}

	/**
	 * Parse a line like: KEY=VALUE, supports quotes and export prefix.
	 * Returns [key, value] or null if the line should be ignored.
	 */
	private function parseLine(string $line): ?array
	{
		$trimmed = trim($line);
		if ($trimmed === '' || $trimmed[0] === '#')
			return null; // empty or comment

		// Allow leading "export "
		if (str_starts_with($trimmed, 'export '))
			$trimmed = trim(substr($trimmed, 7));

		// Split on first '='
		$eqPos = strpos($trimmed, '=');
		if ($eqPos === false)
			return null; // not a valid assignment

		$key = trim(substr($trimmed, 0, $eqPos));
		$valueRaw = trim(substr($trimmed, $eqPos + 1));

		// Validate key (A-Z, a-z, 0-9, _, .)
		if (!preg_match('/^[A-Za-z0-9_.]+$/', $key))
			return null; 

		// Handle inline comments only when NOT quoted
		$isQuoted = ($valueRaw !== '' && ($valueRaw[0] === '"' || $valueRaw[0] === "'"));
		if (!$isQuoted) {
			// Remove inline comments starting with #
			$hashPos = strpos($valueRaw, '#');
			if ($hashPos !== false)
				$valueRaw = rtrim(substr($valueRaw, 0, $hashPos));
		}

		$value = $this->normalizeValue($valueRaw);
		return [$key, $value];
	}

	/** Normalize a raw value, handling quotes and escapes. */
	private function normalizeValue(string $raw): string
	{
		if ($raw === '') return '';

		$q = $raw[0] ?? '';
		if ($q === '"' || $q === "'") {
			// Strip surrounding quotes if present
			if (strlen($raw) >= 2 && $raw[strlen($raw) - 1] === $q) {
				$inner = substr($raw, 1, -1);
				if ($q === '"') {
					// Handle common escape sequences in double quotes
					$inner = str_replace(["\\n", "\\r", "\\t", "\\\"", "\\\\"], ["\n", "\r", "\t", '"', "\\"], $inner);
				}
				return $inner;
			}
		}

		return $raw;
	}

	/** Expand ${VAR} references using loaded env first, then PHP environment. */
	private function expandVariables(string $value): string
	{
		return preg_replace_callback('/\$\{([A-Za-z0-9_\.]+)\}/', function ($m) {
			$k = $m[1];
			if (array_key_exists($k, $this->env)) return $this->env[$k];
			$sys = self::getFromPhpEnv($k);
			return $sys !== null ? $sys : '';
		}, $value) ?? $value;
	}

	/** Export to PHP environment (putenv, $_ENV, $_SERVER). */
	private function export(string $key, string $value): void
	{
		// Values are strings in env context
		putenv("$key=$value");
		$_ENV[$key] = $value;
		$_SERVER[$key] = $value;
	}

	/** Try to get a value from PHP environment. */
	private static function getFromPhpEnv(string $key): ?string
	{
		$v = getenv($key);
		if ($v !== false)
			return (string)$v;
		if (isset($_ENV[$key]))
			return (string)$_ENV[$key];
		if (isset($_SERVER[$key]))
			return (string)$_SERVER[$key];
		return null;
	}

	// ---------- Static convenience ----------

	/**
	 * Static helper to fetch from PHP environment directly.
	 */
	public static function env(string $key, ?string $default = null): ?string
	{
		$v = self::getFromPhpEnv($key);
		return $v !== null ? $v : $default;
	}
}