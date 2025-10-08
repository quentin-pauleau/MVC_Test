<?php
namespace Modules\ORM\Attributes;

use Attribute;
use Modules\ORM\Enums\ForeignFieldRules;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class ForeignFieldOnUpdate
{
	public ForeignFieldRules $rule;
}