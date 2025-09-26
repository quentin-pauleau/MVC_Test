<?php
namespace Modules\Routing\Param;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final readonly class BodyParam extends RouteParam
{
	public function __construct(
		public string $name, 
		public bool $isRequired = true,

		/**
		 * @var string|null name of the parameter in the body
		 */
		public ?string $label = null,
	) {}
}