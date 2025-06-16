<?php
namespace Feature\EntityToDatabase\Attributes;

use Attribute;
use Feature\EntityToDatabase\Enums\ForeignFieldRules;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class ForeignFieldOnUpdate
{
	public ForeignFieldRules $rule;
}