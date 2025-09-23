<?php
namespace Modules\ORM\Attributes;

use Attribute;


/**
 * Bind the entity to a table in the database
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class BindTable
{
	public function __construct(
		public string $name,
		public ?string $schema = null,
		public ?string $alias = null,
	) {}
}