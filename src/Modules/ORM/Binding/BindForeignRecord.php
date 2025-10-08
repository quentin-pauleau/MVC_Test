<?php
namespace Modules\ORM\Binding;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class BindForeignRecord
{

	public function __construct(
		string $targetRecord,
		string $targetRecordFieldName,
	)
	{}
}