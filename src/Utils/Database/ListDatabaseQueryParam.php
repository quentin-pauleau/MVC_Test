<?php
namespace Utils\Database;

use Utils\Generics\ListGeneric;
use Utils\Database\DatabaseQueryParam;

class ListDatabaseQueryParam extends ListGeneric
{
	/**
	 * @var array<int, DatabaseQueryParam> $values
	 */
	public array $values = [];

	
	public function __construct(DatabaseQueryParam ...$values) {
		foreach ($values as $value) {
			$this->values[] = $value;
		}
	}


	/**
	 * Add values to the list
	 * @param DatabaseQueryParam[] $values
	 * @return void
	 */
	public function Add(DatabaseQueryParam ...$values): void {
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
	 * @param DatabaseQueryParam[][] $values
	 * @return void
	 */
	public function AddArray(array ...$arrays): void {
		foreach ($arrays as $array) {
			if (!is_array_of_class($array, DatabaseQueryParam::class)) {
				continue;
			}
			$this->values = array_merge($this->values, $array);
		}
	}


	/**
	 * Remove values from the list
	 * @param DatabaseQueryParam[] $values
	 * @return void
	 */
	public function Remove(DatabaseQueryParam ...$values): void {
		foreach ($values as $value) {
			$key = array_search($value, $this->values);

			if ($key === false) {
				continue;
			}

			array_slice($this->values, $key, $key);
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
	
				array_slice($this->values, $key, $key);
			}
		}
	}


	/**
	 * Remove values in the given arrays from the list
	 * @param DatabaseQueryParam[][] $arrays
	 * @return void
	 */
	public function RemoveArray(array ...$arrays): void {
		parent::RemoveArray($arrays);
	}


	/**
	 * Return a value at a given index
	 * @param int $index
	 * @return DatabaseQueryParam
	 */
	public function Get(int $index): DatabaseQueryParam {
		return $this->values[$index];
	}

	
	public function Current(): DatabaseQueryParam {
		return $this->values[$this->current_key];
	}
}