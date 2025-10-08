<?php
namespace Modules\ORM\Attributes;

use Attribute;


/**
 * Define if the field is nullable or not
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class NullableField
{}