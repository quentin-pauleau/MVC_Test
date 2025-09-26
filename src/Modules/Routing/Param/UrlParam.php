<?php
namespace Modules\Routing\Param;



final readonly class UrlParam extends RouteParam
{
	public function __construct(
		public string $name,
		public bool $isRequired = true,
		/**
		 * Name in beteween braces in the route pattern.
		 *
		 * @example "/exemple/{id}"
		 */
		public ?string $label = null,
	) {}
}


