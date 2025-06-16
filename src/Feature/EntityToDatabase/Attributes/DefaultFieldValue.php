<?php
namespace Feature\EntityToDatabase\Attributes;

use Attribute;


/**
 * The value used by default in the database for this field
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class DefaultFieldValue
{
	public mixed $value;
}