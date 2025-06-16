<?php
namespace Utils;

use Exception;


class UUID
{
	private string $UUID = "";


	public function GetUUID(): string {
		return $this->UUID;
	}


	public function SetUUIDFromString(string $UUID): void {
		if (!self::IsValid($UUID)) {
			throw new Exception("Invalid UUID");
		}

		$this->UUID = $UUID;
	}


	public function __construct() {
		$this->UUID = self::Generate();
	}

	public static function Generate(): string {
		// Generate 16 bytes (128 bits) of random data or use the data passed into the function.
		$data = random_bytes(16);
		assert(strlen($data) == 16);

		// Set version to 0100
		$data[6] = chr(ord($data[6]) & 0x0f | 0x40);
		// Set bits 6-7 to 10
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80);
		
		// Output the 36 character UUID.
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}


	public static function FromString(string $UUID): UUID {
		if (!self::IsValid($UUID)) {
			throw new Exception("Invalid UUID");
		}
		
		$uuid = new UUID();
		$uuid->UUID = $UUID;
		return $uuid;
	}


	public static function IsValid(string $UUID): bool {
		return preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $UUID) === 1;
	}


	public static function Match(UUID ...$UUID): bool {
		foreach ($UUID as $uuid)
			if ($uuid->UUID !== $UUID[0]->UUID)
				return false;

		return true;
	}

	public static function MatchString(string ...$UUID): bool {
		foreach ($UUID as $uuid) {
			self::IsValid($uuid);

			if ($uuid !== $UUID[0])
				return false;
		}
		
		return true;
	}

	
	public static function Compare(UUID $UUID1, UUID $UUID2): int {
		return strcmp($UUID1->UUID, $UUID2->UUID);
	}


	public function __tostring(): string {
		return $this->UUID;
	}
}