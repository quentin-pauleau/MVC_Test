<?php
namespace Models\Entities;

use Models\Entities\Entity;

class DegresUrgence extends Entity
{
	/**
	 * Id by default, representing "PRIORITAIRE"
	 * @var int
	 */
	public const DEFAULT_ID = 1;

	public string $des = "";
	public bool $is_cli = false;
	public bool $is_four = false;


	public function __tostring(): string {
		return $this->des;
	}
}