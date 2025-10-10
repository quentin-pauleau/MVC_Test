<?php
namespace Modules\Routing\Param;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
abstract readonly class RouteParam
{

	/**
	 * @var string|null name of the targeted parameter
	 */
	public string|null $name;
	
	public function __construct(
		string $name = null,
	) {
		$this->name = $name;
	}
}