<?php
namespace Modules\ORM\Binding;

use Attribute;
use Modules\ORM\Enums\DatabaseTypes;
use Modules\ORM\Enums\ForeignFieldRules;

#[Attribute(Attribute::TARGET_PROPERTY)]
/**
 * Binds a foreign key
 */
final readonly class BindForeignField extends BindKeyField
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
	 * Name of the record class this foreign key is pointing to
	 * @var string
	 */
	public string $foreignRecord;

	/**
	 * Name of the field in the foreign entity that this foreign key points to
	 * by default the primary key
	 * @var string
	 */
	public string|null $foreignRecordField;

	/**
	 * Alias used in queries for avoid ambiguity and conficts with other foreign keys
	 * @var string
	 */
	public string|null $foreignRecordAlias;

	// public string|null $recordObject;

	/**
	 * OnDelete rule for the foreign key
	 * If ForeignKeyOnDelete attribute is used; it has priority over this value
	 * 
	 * @see https://www.postgresql.org/docs/current/ddl-constraints.html#DDL-CONSTRAINTS-FK
	 * @var ForeignFieldRules
	 */
	public ForeignFieldRules|null $OnDelete;


	/**
	 * OnUpdate rule for the foreign key
	 * If ForeignKeyOnDelete attribute is used; it has priority over this value
	 * 
	 * @see https://www.postgresql.org/docs/current/ddl-constraints.html#DDL-CONSTRAINTS-FK
	 * @var ForeignFieldRules
	 */
	public ForeignFieldRules|null $OnUpdate;

	/**
	 * @param string $name Name of the field in the database table
	 * @param \Modules\ORM\Enums\DatabaseTypes|string|null $type Type of the field in the database
	 * @param string|null $keyName Name of the key constraint on this column. Defaults to "fk_{tableName}_{fieldName}".
	 * @param bool $isNullable Whether or not the field can be nullable (null)
	 * @param mixed $default Default value for the field if no value is provided when creating a new record
	 * @param string $foreignRecord Record class name this foreign key is pointing to
	 * @param string|null $foreignField Name of the field in the foreign table
	 * @param string|null $foreignRecordAlias Alias used in queries for avoid ambiguity and conficts with other foreign keys
	 * @param \Modules\ORM\Enums\ForeignFieldRules|null $OnDelete Behavior when deleting records from the foreign table
	 * @param \Modules\ORM\Enums\ForeignFieldRules|null $OnUpdate Behavior when updating records from the foreign table
	 */
	public function __construct(
		string $name,
		DatabaseTypes|string|null $type = null,
		bool $isNullable = false,
		mixed $default = null,
		string|null $keyName = null,
		string $foreignRecord,
		string|null $foreignRecordField = null,
		string|null $foreignRecordAlias = null,
		ForeignFieldRules|null $OnDelete = null,
		ForeignFieldRules|null $OnUpdate = null,
	) {
		parent::__construct(
			$name, 
			$type, 
			$keyName,
		);

		$this->isNullable = $isNullable;
		$this->default = $default;

		$this->foreignRecord = $foreignRecord;
		$this->foreignRecordField = $foreignRecordField;
		$this->foreignRecordAlias = $foreignRecordAlias;
		$this->OnDelete = $OnDelete;
		$this->OnUpdate = $OnUpdate;
	}
}