<?php
namespace Modules\ORM\Binding;

use Attribute;
use Modules\DatabaseConnection\DatabaseInfos\DatabaseField;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class BindPrimaryField extends BindKeyField
{

	public function __construct(
		string $name,
		DatabaseField|string|null $type,
		string|null $keyName,

	) {
		parent::__construct(
			$name, 
			$type,
			$keyName
		);
	}
}