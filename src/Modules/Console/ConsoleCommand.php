<?php
namespace Modules\Console;

use Attribute;

#[Attribute(Attribute::TARGET_FUNCTION | Attribute::IS_REPEATABLE)]
class ConsoleCommand
{
	public function __construct(
		public string $name,
		public string $description = '',
		public bool $hidden = false,
	) {}


}