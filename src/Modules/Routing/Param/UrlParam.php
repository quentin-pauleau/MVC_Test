<?php
namespace Modules\Routing\Param;



final readonly class UrlParam extends RouteParam
{
	/**
	 * Name in beteween braces in the route pattern,
	 * if null the name is expected to be the same as the parameter
	 *
	 * @example "/exemple/{id}"
	 * @var string|null
	 */
	
	public ?string $label;
	public function __construct(
		string $name,
		?string $label = null,
	) {
		parent::__construct($name);
		$this->label = $label ?? $this->name;
	}
}


