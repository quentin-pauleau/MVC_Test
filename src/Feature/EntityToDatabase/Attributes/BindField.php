<?php
namespace Feature\EntityToDatabase\Attributes;

use Attribute;

/**
 * Bind the property to a database field
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class BindField {
	/**
	 * Name of the field in the table
	 * @var string
	 */
	public string $name;

	/**
	 * Type of the field in the table
	 * @var int
	 */
	public int $type;

	public bool $isNullable = false;

	/**
	 * Default value for this field in the database
	 * @var mixed
	 */
	public mixed $default = null;
}