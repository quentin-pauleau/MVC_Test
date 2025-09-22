<?php
namespace Modules\Routing\Action;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Action
{
	public function __construct(
		public string $name,
	) {}
}