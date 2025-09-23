<?php
namespace Modules\ORM\Attributes;

use Attribute;

/**
 * Bind the property to a database field
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class BindField
{
	public function __construct(
		/**
		 * Name of the field in the table
		 * @var string
		 */
		public string $name,

		/**
		 * Type of the field in the table
		 * @var string
		 */
		public string $type,

		/**
		 * Whether or not the field is nullable
		 * @var boolean
		 */
		public bool $isNullable = false,

		/**
		 * Default value for this field in the database
		 * @var mixed
		 */
		public mixed $default = null,
	) { }
}