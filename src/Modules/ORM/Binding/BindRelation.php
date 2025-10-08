<?php
namespace Modules\ORM\Binding;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
abstract readonly class BindRelation
{
	/**
	 * Name of the foreign field property in this record.
	 * @var string
	 */
	public string $foreignFieldName;

	public function __construct(
		string $foreignFieldName,
	) {
		$this->foreignFieldName = $foreignFieldName;
	}
}