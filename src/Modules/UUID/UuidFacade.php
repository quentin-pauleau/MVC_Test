<?php
namespace Modules\UUID;

use InvalidArgumentException;

/**
 * Facade for UUID/ULID generation and validation.
 * Delegates to version-specific classes while keeping a simple static API.
 */
class UuidFacade
{
	// Generation (strings)
	public static function v1(): string { return UuidV1::generate(); }
	public static function v3(string $namespaceUuid, string $name): string { return UuidV3::generate($namespaceUuid, $name); }
	public static function v4(): string { return UuidV4::generate(); }
	public static function v5(string $namespaceUuid, string $name): string { return UuidV5::generate($namespaceUuid, $name); }
	public static function v7(): string { return UuidV7::generate(); }
	public static function ulid(): string { return Ulid::generate(); }

	// Validation
	public static function isValidUuid(string $uuid): bool
	{
		return (bool) preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[13457][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $uuid);
	}

	public static function getUuidVersion(string $uuid): int
	{
		self::isValidUuid($uuid) ?: throw new InvalidArgumentException("$uuid is not a valid Uuid");

		$hex = strtolower(str_replace('-', '', $uuid));
		return hexdec($hex[12]);
	}

	public static function GetUuidClass(string $uuid): string
	{
		return match($v = self::getUuidVersion($uuid))
		{
			1 => UuidV1::class,
			3 => UuidV3::class,
			4 => UuidV4::class,
			5 => UuidV5::class,
			7 => UuidV7::class,
			default => throw new InvalidArgumentException("Unsupported uuid version: $v")
		};
	}

	public static function isUuidVersion(string $uuid, int $version): bool
	{
		return self::getUuidVersion($uuid) === $version;
	}

	public static function compareUuid(string $a, string $b): int
	{
		$a = strtolower($a); $b = strtolower($b); return $a <=> $b;
	}

	public static function isValidUlid(string $ulid): bool
	{
		return Ulid::isValid($ulid);
	}

	public static function compareUlid(string $a, string $b): int
	{
		return Ulid::compare($a, $b);
	}
}