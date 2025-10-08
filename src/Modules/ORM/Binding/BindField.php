<?php
namespace Modules\ORM\Binding;

use Attribute;
use Modules\ORM\Enums\DatabaseTypes;

/**
 * Bind the property to a database field
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class BindField extends BindAbstractField
{
	/**
	 * Whether or not the field is nullable
	 * @var boolean
	 */
	public bool $isNullable;

	/**
	 * Default value for this field in the database
	 * @var mixed
	 */
	public mixed $default;

	/**
	 * @param string|null $name The name of the field. If null, the property name is used
	 * @param \Modules\ORM\Enums\DatabaseTypes|string|null $type The type of the field. If null, it will be set to match the property type
	 * @param bool $isNullable Whether or not the field can be null
	 * @param mixed $default The default value for this field
	 */
	public function __construct(
		string|null $name,
		DatabaseTypes|string|null $type,
		bool $isNullable = false,
		mixed $default = null,
	) {
		parent::__construct(
			$name, 
			$type,
		);
		$this->isNullable = $isNullable;
		$this->default = $default;
	}
}