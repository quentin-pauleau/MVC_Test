<?php
namespace Core\Generics;

use Iterator;
use Countable;
use Core\Generics\PairGeneric;

/**
 * A generic class for manager of values
 * 
 * An object representation of an array <string, T> with T the type of the managed values
 * 
 * @template-contravariant string
 * @template-covariant T
 */
abstract class ManagerGeneric implements Iterator, Countable
{
	/**
	 * @var array<string, T> $values
	 */
	protected array $values;

	/**
	 * @var string $current_name the current key in a foreach loop
	 */
	protected string $current_name;


	#region Add Functions 
	/**
	 * Add values to the list
	 * @param string $key
	 * @return self
	 */
	abstract protected function Add(string $key): self;


	/**
	 * Add all values in the given list to the list
	 * @param PairGeneric<string, T>[] $pairs
	 * @return void
	 */
	abstract protected function AddPair(): self;


	/**
	 * Add all values in the given list to the list
	 * @param self $values
	 * @return void
	 */
	abstract protected function AddManager(): self;


	/**
	 * Add all values in the given array to the list
	 * @param T[][] $values
	 * @return void
	 */
	protected function AddArray(array ...$arrays): self {
		foreach ($arrays as $array) {
			$this->values = array_merge($this->values = $array);
		}
		return $this;
	}
	#endregion


	#region Remove Functions 
	/**
	 * Remove values from the list
	 * @param T[] $values
	 * @return self
	 */
	abstract protected function Remove(): self;

	protected function RemoveAtKey(string ...$key): self {
		foreach ($key as $k) {
			unset($this->values[$k]);
		}
		return $this;
	}

	/**
	 * Remove all values in the given lists from the list
	 * @return self
	 */
	abstract protected function RemoveList(): self;

	
	/**
	 * Remove values in the given arrays from the list
	 * @param T[][] $arrays
	 * @return self
	 */
	protected function RemoveArray(array ...$arrays): self {
		foreach ($arrays as $array) {
			foreach ($array as $value) {
				$key = array_search($value, $this->values);

				if ($key === false) {
					continue;
				}
	
				array_slice($this->values, $key, $key);
			}
		}

		return $this;
	}
	#endregion


	#region Get Functions 
	/**
	 * Return a value with a given name
	 * @param string $name
	 * @return T
	 */
	abstract protected function Get(string $name);


	/**
	 * Return a value with a given name
	 * @param string $name
	 * @return PairGeneric<string, T>
	 */
	abstract protected function GetPair(string $name): PairGeneric;

	/**
	 * Return an array containing all values of the list
	 * @return T[]
	 */
	protected function GetAll(): array {
		return $this->values;
	}


	/**
	 * Summary of GetKeys
	 * @return array
	 */
	protected function GetNames(): array {
		return array_keys($this->values);
	}

	#endregion


	public function Count(): int {
		return count($this->values);
	}


	protected function IsEmpty(): bool {
		return $this->values === [];
	}

	protected function Clear(): self {
		$this->values = [];
		return $this;
	}


	protected function Has(string $name): bool {
		return isset($this->values[$name]);
	}


	#region Iterable Implementation 

	public function key(): string {
		return $this->current_name;
	}

	public function next(): void {
		$keys = array_keys($this->values);
		$key = array_search($this->current_name, $keys);
		$this->current_name = $keys[$key + 1];
	}

	public function rewind(): void {
		$this->current_name = array_key_first($this->values);
	}

	public function valid(): bool {
		return isset($this->values[$this->current_name]);
	}

	#endregion
}