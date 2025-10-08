<?php
namespace Modules\ORM\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
abstract readonly class KeyAbstract
{
	public function __construct(
		/**
		 * Name of the unique key in the database
		 * @var string|null
		 */
		public string|null $name = null,
	) {}
}