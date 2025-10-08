<?php
namespace Modules\ORM\Binding;

use Attribute;
use Modules\ORM\Enums\DatabaseTypes;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class BindUniqueField extends BindAbstractField
{
	public bool $isNullable;

	public function __construct(
		string $name,
		DatabaseTypes|string|null $type,
		bool $isNullable = false,
	) {
		parent::__construct(
			$name, 
			$type
		);

		$this->isNullable = $isNullable;
	}
}