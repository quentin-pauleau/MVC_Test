<?php
namespace Modules\ORM\Binding;

use Attribute;

/**
 * @experimental
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class BindOneRelation extends BindRelation
{
	/**
	 * Name of the binded foreign property in the record
	 * @var string
	 */
	public string $foreignBindName;

	public function __construct(
		string $foreignBindName
	) {
		$this->foreignBindName = $foreignBindName;
	}
}