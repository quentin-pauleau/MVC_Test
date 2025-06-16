<?php
namespace Traits;

use Exception;

/**
 * Make a class Unclonable
 */
trait Unclonable
{
	private function __clone() {
		throw new Exception("This ".self::class." is unclonable.");
	}
}