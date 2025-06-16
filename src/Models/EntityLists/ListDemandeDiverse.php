<?php
namespace Models\EntityLists;

use Models\EntityLists\ListEntity;
use Models\Entities\DemandeDiverse;

class ListDemandeDiverse extends ListEntity
{
	/**
	 * @var array<int, DemandeDiverse> $values
	 */
	public array $values = [];

	
	public function __construct(DemandeDiverse ...$values) {
		foreach ($values as $value) {
			$this->values[] = $value;
		}
	}


	/**
	 * Add values to the list
	 * @param DemandeDiverse[] $values
	 * @return void
	 */
	public function Add(DemandeDiverse ...$values): void {
		foreach ($values as $value) {
			$this->values[] = $value;
		}
	}


	/**
	 * Add all values in the given list to the list
	 * @param self[] $lists
	 * @return void
	 */
	public function AddList(self ...$lists): void {
		foreach ($lists as $list) {
			$this->values = array_merge($this->values, $list->GetAll());
		}
	}


	/**
	 * Add all values in the given array to the list
	 * @param DemandeDiverse[][] $values
	 * @return void
	 */
	public function AddArray(array ...$arrays): void {
		foreach ($arrays as $array) {
			if (!is_array_of_class($array, DemandeDiverse::class)) {
				continue;
			}
			$this->values = array_merge($this->values, $array);
		}
	}


	/**
	 * Remove values from the list
	 * @param DemandeDiverse[] $values
	 * @return void
	 */
	public function Remove(DemandeDiverse ...$values): void {
		foreach ($values as $value) {
			$key = array_search($value, $this->values);

			if ($key === false) {
				continue;
			}

			array_splice($this->values, $key, 1);
		}
	}


	/**
	 * Remove all values in the given lists from the list
	 * @param self[] $values
	 * @return void
	 */
	public function RemoveList(self ...$lists): void {
		foreach ($lists as $list) {
			foreach ($list->GetAll() as $value) {
				$key = array_search($value, $this->values);

				if ($key === false) {
					continue;
				}
	
				array_splice($this->values, $key, 1);
			}
		}
	}


	/**
	 * Remove values in the given arrays from the list
	 * @param DemandeDiverse[][] $arrays
	 * @return void
	 */
	public function RemoveArray(array ...$arrays): void {
		parent::RemoveArray($arrays);
	}


	/**
	 * Return a value at a given index
	 * @param int $index
	 * @return DemandeDiverse
	 */
	public function Get(int $index): DemandeDiverse {
		return $this->values[$index];
	}

	
	/**
	 * Return the current demande
	 * @return DemandeDiverse
	 */
	public function Current(): DemandeDiverse {
		return $this->values[$this->current_key];
	}
}