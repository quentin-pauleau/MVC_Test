<?php
namespace Modules\UUID;

use InvalidArgumentException;

/**
 * Base class for UUID value objects.
 * Provides validation, comparison, and common utilities.
 */
abstract class AbstractUuid
{
	protected string $value;

	protected function __construct(string $value)
	{
		static::assertValid($value);
		$this->value = strtolower($value);
	}

	// ----- Instance API -----

	public function toString(): string { return $this->value; }
	public function __toString(): string { return $this->value; }

	public function equals(self|string $other): bool
	{
		$a = $this->value;
		$b = $other instanceof self ? $other->value : strtolower($other);
		return $a === $b;
	}

	// ----- Static API -----

	public static function fromString(string $uuid): static
	{
		return new static($uuid);
	}

	public static function isValid(string $uuid): bool
	{
		$v = static::versionDigit();
		return (bool) preg_match("/^[0-9a-f]{8}-[0-9a-f]{4}-{$v}[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i", $uuid);
	}

	protected static function assertValid(string $uuid): void
	{
		if (!static::isValid($uuid)) {
			throw new InvalidArgumentException('Invalid UUID v' . static::versionDigit() . ' string');
		}
	}

	public static function compare(self|string $a, self|string $b): int
	{
		$as = $a instanceof self ? $a->value : strtolower($a);
		$bs = $b instanceof self ? $b->value : strtolower($b);
		return $as <=> $bs;
	}

	public static function version(): int { return (int) static::versionDigit(); }
	protected static function versionDigit(): string { return 'x'; }

	// ----- Helpers for subclasses -----

	protected static function uuidToBytes(string $uuid): string
	{
		$hex = strtolower(str_replace('-', '', $uuid));
		if (strlen($hex) !== 32 || !ctype_xdigit($hex))
			throw new InvalidArgumentException('Invalid UUID input');
		
		$bin = hex2bin($hex);

		if ($bin === false)
			throw new InvalidArgumentException('Invalid UUID hex');
		
		return $bin;
	}

	protected static function bytesToUuid(string $bytes): string
	{
		if (strlen($bytes) !== 16)
			throw new InvalidArgumentException('UUID binary length must be 16 bytes');
		
		$hex = bin2hex($bytes);
		return strtolower(vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split($hex, 4)));
	}
}