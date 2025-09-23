<?php
namespace Modules\Console;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class ConsoleCommandGroup
{

	public function __construct(
		public string $name,
		public string $description = '',
		public bool $hidden = false,
	) {}

}