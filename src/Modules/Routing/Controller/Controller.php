<?php
namespace Modules\Routing\Controller;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Controller {

	public function __construct(
		public ?string $name = null,
		public string $route_prefix = '',
	) {}
}