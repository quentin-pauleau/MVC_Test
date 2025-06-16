<?php
namespace Models\EntityLists;

use Models\Entities\Entity;
use Utils\Generics\ListGeneric;

abstract class ListEntity extends ListGeneric
{
	/**
	 * @var array<int, Entity> $values
	 */
	public array $values = [];


	/**
	 * Add all values in the given array to the list
	 * @param Entity[][] $values
	 * @return void
	 */
	public function AddArray(array ...$arrays): void {
		foreach ($arrays as $array) {
			if (!is_array_of_class($array, Entity::class)) {
				continue;
			}
			$this->values = array_merge($this->values, $array);
		}
	}


	/**
	 * Remove values in the given arrays from the list
	 * @param Entity[][] $arrays
	 * @return void
	 */
	public function RemoveArray(array ...$arrays): void {
		parent::RemoveArray($arrays);
	}


	/**
	 * Return a value at a given index
	 * @param int $index
	 * @return Entity
	 */
	public function Get(int $index): Entity {
		return $this->values[$index];
	}


	/**
	 * Summary of GetAll
	 * @return Entity[]
	 */
	public function GetAll(): array {
		return parent::GetAll();
	}

	
	/**
	 * Return the current value, use when itirating the list with a foreach
	 * @return Entity
	 */
	public function Current(): Entity {
		return $this->values[$this->current_key];
	}
}