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


	public function Save(): void {
		$this->GetEntityManager()->Save($this);
	}
}