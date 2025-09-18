<?php
namespace Modules\HTMLElement\Managers;


/**
 * Manager that assure tabindex uniqueness
 * 
 * used by :
 * - Dropdown
 */
class TabIndexManager
{
	private static ?TabIndexManager $instance = null;

	public static function getManager(): TabIndexManager {
		return self::$instance ?? new self;
	}

	private function __construct() {}

	private int $currentIndex = 0;


	public function getNewIndex(): int
	{
		return ++$this->currentIndex;
	}
}
