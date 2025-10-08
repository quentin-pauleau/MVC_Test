<?php
namespace Modules\ORM\Attributes;

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


	public function __construct(string $entity) {
		$this->entity = $entity;
	}
}