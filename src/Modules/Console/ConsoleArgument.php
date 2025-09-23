<?php
namespace Modules\Console;

use Attribute;

#[Attribute(Attribute::TARGET_FUNCTION)]
class ConsoleArgument
{
	public function __construct(
		public string $name, 
		public mixed $default = null
	)
	{
		
	}
}