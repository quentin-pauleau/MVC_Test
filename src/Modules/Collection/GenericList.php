<?php
namespace Modules\Collection;

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
class GenericList extends AbstractList
{
	protected string $class;


	public function __construct(string $class = "any", ...$values) {
		$this->class = $class;

		$this->Add();
	}


	public function GetListClass(): string {
		return $this->class;
	}
	

	#region Add Functions
	/**
	 * Add values to the list
	 * @param T[] $values
	 * @return void
	 */
	public function Add(...$values): void {
		foreach ($values as $value) {
			if (is_a($value, $this->class) == false)
				throw new \Exception("Value must be of type {$this->class}");

			$this->values[] = $value;
		}
	}


	/**
	 * Add all values in the given list to the list
	 * @return void
	 */
	public function AddList(self ...$lists = null): void {
		foreach ($lists as $list) {
			if ($list === null || $list->IsEmpty() || $this->GetListClass() !== $list->GetListClass())
				throw new \Exception("List must be a list of type {$this->class}");

			foreach ($list as $value)
				$this->values[] = $value;
		}
	}


	/**
	 * Add all values in the given array to the list
	 * @param T[][] $values
	 * @return void
	 */
	public function AddArray(array ...$arrays): void {
		foreach ($arrays as $array)
			$this->Add(...$array);
	}
	#endregion


	#region Remove Functions
	/**
	 * Remove values from the list
	 * @param T[] $values
	 * @return void
	 */
	public function Remove(): void {
		foreach ($values as $value) {
			$key = array_search($value, $this->values);
			if ($key === false)
				continue;
			
			array_splice($this->values, $key, 1);
		}
	}


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
		foreach ($arrays as $array)
			foreach ($array as $value) {
				$key = array_search($value, $this->values);

				if ($key === false)
					continue;
	
				array_splice($this->values, $key, 1);
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


	public function __clone(): void {
		$values = [];

		foreach ($this->values as $value)
			$values[] = clone $value;

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