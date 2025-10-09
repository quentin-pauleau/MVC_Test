<?php
namespace Modules\ORM\Binding;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
abstract readonly class BindRelation
{
	public function __construct() {}
}