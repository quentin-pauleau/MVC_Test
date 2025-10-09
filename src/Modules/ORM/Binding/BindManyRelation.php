<?php
namespace Modules\ORM\Binding;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class BindManyRelation extends BindRelation
{
	public function __construct(
		
	) {
	}
}