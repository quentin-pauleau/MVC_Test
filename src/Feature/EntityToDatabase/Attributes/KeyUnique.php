<?php
namespace Feature\EntityToDatabase\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class KeyUnique
{
	/**
	 * Name of the unique key in the database
	 * @var string|null
	 */
	public string|null $name = null;
}