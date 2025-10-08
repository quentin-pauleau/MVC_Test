<?php
namespace Modules\ORM\Binding;

use Attribute;
use Modules\ORM\Enums\DatabaseTypes;

#[Attribute(Attribute::TARGET_PROPERTY)]
abstract readonly class BindKeyField extends BindAbstractField
{
	/**
	 * Name of the unique key in the database
	 * @var string|null
	 */
	public string|null $keyName;

	/**
	 * Summary of __construct
	 * @param string $name
	 * @param \Modules\ORM\Enums\DatabaseTypes|string|null $type
	 * @param string|null $key
	 */
	public function __construct(
		string $name,
		DatabaseTypes|string|null $type = null,
		string|null $keyName = null,
	) {
		parent::__construct(
			$name,
			$type,
		);

		$this->keyName = $keyName;
	}
}