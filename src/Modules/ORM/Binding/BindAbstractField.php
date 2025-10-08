<?php
namespace Modules\ORM\Binding;

use Attribute;
use Modules\ORM\Enums\DatabaseTypes;


/**
 * Bind the property to a database field
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
abstract readonly class BindAbstractField
{
	/**
	 * Name of the field in the table
	 * @var string
	 */
	public string $name;

	/**
	 * Type of the field in the table,
	 * when null the default database type for that PHP type will be used
	 * - null => NULL
	 * - bool => tinyint(1)
	 * - int => int
	 * - float => float
	 * - string => varchar
	 * - iterable => jsonb
	 * - object => jsonb
	 * - resource => blob
	 * - DateTime => datetime
	 * - DateTimeImmutable => datetime
	 * - DateInterval => interval
	 * 
	 * @var string|null
	 */
	public string|null $type;

	public function __construct(
		string $name,
		DatabaseTypes|string $type,
	) {
		$this->name = $name;
		$this->type = (string)$type;
	}
}