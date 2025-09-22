<?php
namespace Modules\ORM\RecordManager;

use Traits\AutoIncrementedId;

/**
 * Class Entity
 */
trait DatabaseRecord
{
	use AutoIncrementedId;

	public function __construct() {}


	private static RecordManager $recordManager;


	abstract public static function GetManager(): RecordManager;

	public static function TryFind(int $id): self|null {
		return self::GetManager()->TryFind($id);
	}

	public static function Find(int $id): self {
		return self::GetManager()->Find($id);
	}

	public static function FindAll(): array {
		return self::GetManager()->FindAll();
	}

	public static function FindMatch(array $params): array {
		return self::GetManager()->FindMatch($params);
	}

	public static function Any(int $id): bool {
		return self::GetManager()->Any($id);
	}


	public function Save(): void {
		self::GetManager()->Save($this);
	}

	public function New(): self {
		return self::Find(
			self::GetManager()->New(clone $this)
		);
	}

	public function Update(): bool {
		return self::GetManager()->Update($this) ?? false;
	}

	public function TryUpdate(): bool {
		return self::GetManager()->TryUpdate($this) ?? false;
	}

	public function Delete(): bool {
		return self::GetManager()->Delete($this);
	}

	public function TryDelete(): bool {
		return self::GetManager()->TryDelete($this);
	}
}