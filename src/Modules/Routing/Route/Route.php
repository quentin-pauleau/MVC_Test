<?php
namespace Modules\Routing\Route;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
abstract class Route
{
	public function __construct(
		public string $path = '',
	) {}
}
