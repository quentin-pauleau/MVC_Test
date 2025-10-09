<?php
namespace Modules\ORM\Binding;

use Attribute;
use Modules\DatabaseConnection\DatabaseInfos\DatabaseField;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class BindPrimaryField extends BindKeyField
{
	public callable|null $defaultValueGenerator;

	/**
	 * @param string|null $name Name of the field in the database, if null the property name is used
	 * @param DatabaseField|string|null $type Type of the field in database, it will be determined from the property type
	 * @param string|null $keyName Name of the key in the database
	 * @param callable|null $defaultValueGenerator Function used to generate a default value when saving a new record
	 */
	public function __construct(
		string|null $name,
		DatabaseField|string|null $type,
		string|null $keyName,
		callable|null $defaultValueGenerator = null
	) {
		parent::__construct(
			$name, 
			$type,
			$keyName
		);

		$this->defaultValueGenerator = $defaultValueGenerator;
	}
}