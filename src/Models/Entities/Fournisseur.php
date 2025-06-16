<?php
namespace Models\Entities;

use Models\Entities\Entity;


class Fournisseur extends Entity
{
	public string $name = "";

	public function GetFullName(): string {
		return ($this->name != "") ? $this->name : "sans nom";
	}

	public function __tostring(): string {
		return $this->GetFullName();
	}
}