<?php
namespace Modules\ORM\RecordManager;

use Traits\AutoIncrementedId;

/**
 * Class Entity
 */
abstract class DatabaseRecord
{
	use AutoIncrementedId;

	abstract public function __construct();


	private static RecordManager $recordManager;


	public static function GetEntityManager(): RecordManager {
		return new RecordManager(self::class);
	}


	public static function Find(int $id): self|null {
		return self::GetEntityManager()->Find($id);
	}

	public static function ForceFind(int $id): self {
		return self::GetEntityManager()->ForceFind($id);
	}

	public static function FindAll(): array {
		return self::GetEntityManager()->FindAll();
	}

	public static function FindMatch(array $params): array {
		return self::GetEntityManager()->FindMatch($params);
	}

	public static function Any(int $id): bool {
		return self::GetEntityManager()->Any($id);
	}


	public function Save(): void {
		self::GetEntityManager()->Save($this);
	}

	public function New(): self {
		$id = self::GetEntityManager()->New(clone $this);
		return self::Find($id);
	}

	public function ForceUpdate(): bool {
		return self::GetEntityManager()->ForceUpdate($this) ?? false;
	}

	public function TryUpdate(): bool {
		return self::GetEntityManager()->TryUpdate($this) ?? false;
	}

	public function Delete(): bool {
		return self::GetEntityManager()->Delete($this);
	}
}