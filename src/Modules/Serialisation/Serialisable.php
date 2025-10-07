<?php
namespace Modules\Serialisation;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class Serialisable
{
	public function __construct(
		/**
		 * @var string|null the name of the property to serialise, if not set it will use the property name.
		 */
		public ?string $name = null,

		/**
		 * @var bool if the property should be serialise when null
		 */
		public bool $isSerialisedWhenNull = false,
	) {}
}