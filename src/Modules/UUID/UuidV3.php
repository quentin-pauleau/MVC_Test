<?php
namespace Modules\UUID;

use InvalidArgumentException;

/** UUID v3: name-based MD5 */
class UuidV3 extends Uuid
{
	protected static function versionDigit(): string { return '3'; }

	public static function generate(string $namespaceUuid, string $name): self
	{
		// Namespace must be a valid UUID of any supported version
		$nsBytes = parent::uuidToBytes($namespaceUuid);
		$hash = md5($nsBytes . $name, true);
		// Set version and variant
		$hash[6] = chr((ord($hash[6]) & 0x0f) | 0x30);
		$hash[8] = chr((ord($hash[8]) & 0x3f) | 0x80);
		$uuid = parent::bytesToUuid($hash);
		return new self($uuid);
	}
}