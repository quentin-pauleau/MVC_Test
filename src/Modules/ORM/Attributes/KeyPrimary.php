<?php
namespace Modules\ORM\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class KeyPrimary extends KeyAbstract
{
	public function __construct()
	{}
}