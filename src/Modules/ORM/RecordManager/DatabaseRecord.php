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
		return RecordManager::Get(static::class);
	}


	public static function TryFind(mixed $id): static|null {
		return static::GetManager()->TryFind($id);
	}

	public static function Find(mixed $id): static {
		return static::GetManager()->Find($id);
	}

	public static function FindAll(): array {
		return static::GetManager()->FindAll();
	}

	public static function FindMatch(array $params): array {
		return static::GetManager()->FindMatch($params);
	}

	public static function Any(mixed $id): bool {
		return static::GetManager()->Any($id);
	}


	public function Exist(): bool {
		return static::Any($this->id);
	}


	public function Save(): bool {
		static::GetManager()->Save($this);
		return true;
	}

	public function New(): static {
		return static::Find(
			static::GetManager()->New(clone $this)
		);
	}

	public function Update(): bool {
		return static::GetManager()->Update($this);
	}

	public function TryUpdate(): bool {
		return static::GetManager()->TryUpdate($this);
	}

	public function Delete(): bool {
		return static::GetManager()->Delete($this);
	}

	public function TryDelete(): bool {
		return static::GetManager()->TryDelete($this);
	}

	

	public static function CreateQuery(): RecordManagerQuery {
		return static::GetManager()->CreateQuery();
	}
}