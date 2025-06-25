<?php
namespace Feature\EntityToDatabase\Attributes;

use Attribute;
use Feature\EntityToDatabase\Enums\ForeignFieldRules;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class ForeignFieldOnDelete
{
	public ForeignFieldRules $rule;
}