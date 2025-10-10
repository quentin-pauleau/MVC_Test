<?php
namespace Modules\UUID;

use InvalidArgumentException;

/**
 * Base class for UUID value objects.
 * Provides validation, comparison, and common utilities.
 */
readonly abstract class Uuid
{
	protected string $value;

	final protected function __construct(string $value)
	{
		if (static::isValid($value) == false)
			throw new InvalidArgumentException("Invalid UUID: {$value}");

		$this->value = strtolower($value);
	}

	// ----- Instance API -----

	final public function __toString(): string { return $this->value; }

	final public function equals(self|string $other): bool
	{
		$otherValue = $other instanceof self ? $other->value : strtolower($other);
		return $this->value === $otherValue;
	}

	// ----- Static API -----

	final public static function fromString(string $uuid): static
	{
		return new static($uuid);
	}

	final public static function isValid(string $uuid): bool
	{
		$v = static::version();
		return (bool) preg_match("/^[0-9a-f]{8}-[0-9a-f]{4}-{$v}[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i", $uuid);
	}

	final public static function compare(self|string $a, self|string $b): int
	{
		$as = $a instanceof self ? $a->value : strtolower($a);
		$bs = $b instanceof self ? $b->value : strtolower($b);
		return $as <=> $bs;
	}

	abstract public static function version(): int;

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