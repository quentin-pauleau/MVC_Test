<?php
namespace Modules\UUID;

/** UUID v7: Unix epoch milliseconds + random (RFC 9562) */
class UuidV7 extends Uuid
{
	protected static function versionDigit(): string { return '7'; }

	public static function generate(): self
	{
		$ms = (int) floor(microtime(true) * 1000);
		$bytes = random_bytes(16);

		// 48-bit big-endian timestamp in bytes 0..5
		$bytes[0] = chr(($ms >> 40) & 0xff);
		$bytes[1] = chr(($ms >> 32) & 0xff);
		$bytes[2] = chr(($ms >> 24) & 0xff);
		$bytes[3] = chr(($ms >> 16) & 0xff);
		$bytes[4] = chr(($ms >> 8) & 0xff);
		$bytes[5] = chr($ms & 0xff);

		// Set version and variant
		$bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x70); // version 7
		$bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80); // variant 10

		$uuid = parent::bytesToUuid($bytes);
		return new self($uuid);
	}
}