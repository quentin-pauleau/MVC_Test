<?php
namespace Modules\Routing\Param;

use Attribute;

/**
 * A paramater passed in the request body.
 * 
 * 
 */
#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final readonly class BodyParam extends RouteParam
{
	/**
	 * @var bool if false then this param is optional and will not be required to pass through validation.
	 */
	public bool $isRequired;
	
	/**
	 * @var string|null name of the parameter in the body, if null the name is expected to be the same as the parameter
	 */
	public string|null $label;

	public function __construct(
		string|null $name = null,
		bool $isRequired = true,
		?string $label = null,
	) {
		parent::__construct($name);
		$this->isRequired = $isRequired;
		$this->label = $label ?? $this->name;
	}
}