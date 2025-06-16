<?php
namespace Models\Entities;

use Models\Entities\Entity;

class TypeConges extends Entity
{
	public string $name = '';
	public ?int $order = null;
	public ?bool $active = false;
	public int $dayCount = 0;

	public function __construct(
		string $name = '',
		?int $order = null,
		?bool $active = false,
		int $dayCount = 0
	) {
		$this->name = $name;
		$this->order = $order;
		$this->active = $active;
		$this->dayCount = $dayCount;
	}

	public function __tostring(): string {
		return $this->name;
	}
}