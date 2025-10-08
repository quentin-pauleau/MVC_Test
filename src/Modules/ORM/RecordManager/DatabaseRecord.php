<?php
namespace Modules\ORM\RecordManager;

use Traits\AutoIncrementedId;

/**
 * Class Entity
 */
trait DatabaseRecord
{
	public function __construct() {}


	private static RecordManager $recordManager;


	public static function GetManager(): RecordManager {
		return RecordManager::Get(self::class);
	}


	public static function TryFind(mixed $id): self|null {
		return self::GetManager()->TryFind($id);
	}

	public static function Find(mixed $id): self {
		return self::GetManager()->Find($id);
	}

	public static function FindAll(): array {
		return self::GetManager()->FindAll();
	}

	public static function FindMatch(array $params): array {
		return self::GetManager()->FindMatch($params);
	}

	public static function Any(mixed $id): bool {
		return self::GetManager()->Any($id);
	}


	public function Exist(): bool {
		return self::Any($this->id);
	}


	public function Save(): bool {
		self::GetManager()->Save($this);
		return true;
	}

	public function New(): self {
		return self::Find(
			self::GetManager()->New(clone $this)
		);
	}

	public function Update(): bool {
		return self::GetManager()->Update($this);
	}

	public function TryUpdate(): bool {
		return self::GetManager()->TryUpdate($this);
	}

	public function Delete(): bool {
		return self::GetManager()->Delete($this);
	}

	public function TryDelete(): bool {
		return self::GetManager()->TryDelete($this);
	}
}