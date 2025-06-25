<?php
namespace Core\Generics;

use Iterator;
use Countable;

/**
 * A generic class for list of values
 * 
 * An object representation of an array <int, T> with T the type of the managed values
 * 
 * @template-contravariant int
 * @template-covariant T
 */
abstract class ListGeneric implements Iterator, Countable
{
	/**
	 * @var array<int, T> $list
	 */
	public array $values;

	/**
	 * @var int $current_key the current key in a foreach loop
	 */
	protected ?int $current_key;


	#region Add Functions
	/**
	 * Add values to the list
	 * @param T[] $values
	 * @return void
	 */
	abstract public function Add(): void;


	/**
	 * Add all values in the given list to the list
	 * @return void
	 */
	abstract public function AddList(): void;


	/**
	 * Add all values in the given array to the list
	 * @param T[][] $values
	 * @return void
	 */
	public function AddArray(array ...$arrays): void {
		foreach ($arrays as $array) {
			foreach($array as $value) {
				$this->values[] = $value;
			}
		}
	}
	#endregion


	#region Remove Functions
	/**
	 * Remove values from the list
	 * @param T[] $values
	 * @return void
	 */
	abstract public function Remove(): void;


	/**
	 * Remove values from the list
	 * @param T[] $values
	 * @return bool true if the value was removed else false
	 */
	public function RemoveAt(int $index): bool {	
		return array_splice($this->values, $index, 1) != [];
	}

	/**
	 * Remove all values in the given lists from the list
	 * @return void
	 */
	abstract public function RemoveList(): void;

	
	/**
	 * Remove values in the given arrays from the list
	 * @param T[][] $arrays
	 * @return void
	 */
	public function RemoveArray(array ...$arrays): void {
		foreach ($arrays as $array) {
			foreach ($array as $value) {
				$key = array_search($value, $this->values);

				if ($key === false) {
					continue;
				}
	
				array_splice($this->values, $key, 1);
			}
		}
	}
	#endregion Remove Functions


	#region get functions
	/**
	 * Return a value at a given index
	 * @param int $index
	 * @return T
	 */
	public function Get(int $index) {
		return $this->values[$index] ?? null;
	}


	/**
	 * Return a value at a given index
	 * @param int $index
	 * @return T
	 */
	public function GetFirst() {
		return $this->values[0] ?? null;
	}


	/**
	 * Return a value at a given index
	 * @param int $index
	 * @return T
	 */
	public function GetLast() {
		return $this->values[count($this) - 1] ?? null;
	}


	/**
	 * Return an array containing all values of the list
	 * @return T[]
	 */
	public function GetAll(): array {
		return $this->values;
	}

	/**
	 * Summary of GetKeys
	 * @return array
	 */
	public function GetKeys(): array {
		return array_keys($this->values);
	}

	public function IsEmpty(): bool {
		return $this->values === [];
	}

	#endregion get functions


	public function __clone() {
		$values = [];

		foreach ($this->values as $value) {
			$values[] = clone $value;
		}

		$this->values = $values;
	}
	

	#region

	public function key(): int {
		return $this->current_key;
	}

	public function next(): void {
		$this->current_key++;
	}

	public function rewind(): void {
		$this->current_key = array_key_first($this->values);
	}

	public function valid(): bool {
		return isset($this->values[$this->current_key]);
	}

	public function Count(): int {
		return count($this->values);
	}

	#endregion
}