<?php
namespace Models\Entities;

use Models\Entities\Entity;



class EtatModel extends Entity
{
	public string $des;
	public bool $is_cli = false;
	public bool $is_four = false;
	public bool $lignes = false;
	public bool $dem = false;

	public function __tostring(): string {
		return $this->GetName();
	}

	/**
	 * Return the state name
	 * @return string
	 */
	public function GetName(): string {
		// get the name after the 1st '-' in the descrption of the state
		return explode('-', $this->des, 2)[1];
	}
}