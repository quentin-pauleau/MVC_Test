<?php
namespace Models\Entities;

use Models\Entities\Entity;
use Models\ModelTypeLivraison;


class TypeLivraison extends Entity
{
	public string $des = "";

	public function __tostring(): string {
		return $this->des;
	}
}