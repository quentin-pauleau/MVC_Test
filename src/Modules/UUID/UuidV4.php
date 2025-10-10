<?php
namespace Modules\UUID;

/** UUID v4: random */
final readonly class UuidV4 extends Uuid
{
	public static function version(): int { return 4; }

	public static function generate(): self
	{
		$bytes = random_bytes(16);
		$bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40); // version 4
		$bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80); // variant 10
		$uuid = parent::bytesToUuid($bytes);
		return new self($uuid);
	}
}