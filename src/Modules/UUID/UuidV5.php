<?php
namespace Modules\UUID;

/** UUID v5: name-based SHA-1 */
class UuidV5 extends Uuid
{
	protected static function versionDigit(): string { return '5'; }

	public static function generate(string $namespaceUuid, string $name): self
	{
		$nsBytes = parent::uuidToBytes($namespaceUuid);
		$hash = substr(sha1($nsBytes . $name, true), 0, 16);
		$hash[6] = chr((ord($hash[6]) & 0x0f) | 0x50); // version 5
		$hash[8] = chr((ord($hash[8]) & 0x3f) | 0x80); // variant 10
		$uuid = parent::bytesToUuid($hash);
		return new self($uuid);
	}
}