<?php
namespace Modules\Routing\Param;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
abstract readonly class RouteParam
{
	public function __construct(
		/**
		 * @var string name of the parameter in the route
		 */
		public string $name, 
		

		/**
		 * @var bool if false then this param is optional and will not be required to pass through validation.
		 */
		public bool $isRequired, 
	) {}
}