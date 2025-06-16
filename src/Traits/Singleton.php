<?php
namespace Traits;

use Traits\Unclonable;

/**
 * Represent an object with only one instance at all time,
 * {@see self::GetInstance()} is used to access and/or create an instance
 */
trait Singleton
{
	use Unclonable;
	private static ?self $instance = null;

	public static function GetInstance(): self {
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	abstract protected function __construct();
}
