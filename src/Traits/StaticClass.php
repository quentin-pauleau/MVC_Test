<?php
namespace Traits;

use Traits\Unclonable;
use Exception;

/**
 * Make a class "static" by unseting an
 */
trait StaticClass
{
	use Unclonable;
	
	private function __contruct(): void {
		throw new Exception(self::class." is a static class and should not be instanciated.");
	}
}