<?php
namespace Feature\EntityToDatabase\Attributes;

use Attribute;

/**
 * Target entity attribute
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class TargetEntity {
	/**
	 * Entity the model is manipulating
	 * @var string
	 */
	public string $entity;
}