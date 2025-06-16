<?php
namespace Models\Entities;

use Models\Entities\Entity;


class Group extends Entity
{
	public string $description;

	public function __tostring():string {
		return $this->description;
	}
}