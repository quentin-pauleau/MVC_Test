<?php
namespace Feature\EntityToDatabase\Attributes;

use Attribute;


/**
 * Bind the entity to a table in the database
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class BindTable
{
	public string $name;
}