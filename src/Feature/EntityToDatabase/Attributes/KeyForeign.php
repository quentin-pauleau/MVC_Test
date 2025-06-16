<?php
namespace Feature\EntityToDatabase\Attributes;

use Attribute;
use Feature\EntityToDatabase\Enums\ForeignFieldRules;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class KeyForeign
{
	/**
	 * Name of the entity this foreign key is pointing to
	 * @var string
	 */
	public string $foreignEntity;

	/**
	 * Name of the field in the foreign entity that this foreign key points to
	 * by default the primary key
	 * @var string
	 */
	public string $foreignField = 'id';

	/**
	 * Name of the foreign key in the database
	 * @var string|null
	 */
	public string|null $name = null;


	/**
	 * OnDelete rule for the foreign key
	 * If ForeignKeyOnDelete attribute is used, it has priority over this value
	 * 
	 * @see https://www.postgresql.org/docs/current/ddl-constraints.html#DDL-CONSTRAINTS-FK
	 * @var ForeignFieldRules
	 */
	public ForeignFieldRules|null $OnDelete;


	/**
	 * OnUpdate rule for the foreign key
	 * If ForeignKeyOnDelete attribute is used, it has priority over this value
	 * 
	 * @see https://www.postgresql.org/docs/current/ddl-constraints.html#DDL-CONSTRAINTS-FK
	 * @var ForeignFieldRules
	 */
	public ForeignFieldRules|null $OnUpdate;
}