<?php
namespace Core\Session;

use Traits\Singleton;
use Core\Generics\ManagerGeneric;
use Core\Generics\PairGeneric;


/**
 * Collection storing persistant data
 * call the name of the collection to reaccess it, if the data isnt accessed it will be destroyed
 * 
 * @template-contravariant string
 * @template-covariant mixed|iterable
 */
final class SessionDataCollection extends SessionAbstractCollection
{
	private const ARRAY_VALUE = 'data';

	private function __construct(string $name) {
		$_SESSION[self::ARRAY_VALUE] ??= [];

		$_SESSION[self::ARRAY_VALUE][$name] ??= [];

		$this->values = $_SESSION[self::ARRAY_VALUE][$name];
		
		Session::GetInstance()->KeepCollection($name);
	}

	/**
	 * Summary of Get
	 * @param string $name
	 * @return mixed|iterable|null
	 */
	public function Get(string $name) {
		return $this->values[$name] ?? null;
	}

	public function Has(string $name): bool {
		return parent::Has($name);
	}


	protected function Add(string $key, $value = null): self {
		$this->values[$key] = $value;
		return $this;
	}


	public function Clear(): self {
		parent::Clear();
		return $this;
	}
	
	/**
	 * Summary of current
	 * @return mixed|iterable
	 */
	public function current(): string {
		return $this->values[$this->current_name];
	}

	
	/**
	 * Filter the value as an string
	 * 
	 * using filter_var with FILTER_DEFAULT
	 * @param string $name
	 * @param bool $quotes_allowed if false FILTER_FLAG_NO_ENCODE_QUOTES is added to the filter
	 * @return string|false|null
	 */
	public function FilterString(string $name, bool $quotes_allowed = false): ?string {
		if (!isset($this->values[$name])) {
			return null;
		}

		return filter_string($this->values[$name], $quotes_allowed);
	}


	/**
	 * Filter the value as an integer
	 * 
	 * using filter_var with FILTER_VALIDATE_INT
	 * @param string $name
	 * @return int|false|null
	 */
	public function FilterInt(string $name) {
		if (!isset($this->values[$name])) {
			return null;
		}

		return filter_int($this->values[$name]);
	}
	
	
	/**
	 * Filter the value as an float
	 * 
	 * using filter_var with FILTER_VALIDATE_FLOAT
	 * @param string $name
	 * @return float|false|null
	 */
	public function FilterFloat(string $name) {
		if (!isset($this->values[$name])) {
			return null;
		}

		return filter_float($this->values[$name]);
	}


	/**
	 * Filter the value as an boolean
	 * 
	 * using filter_var with FILTER_VALIDATE_BOOLEAN
	 * @param string $name
	 * @return bool|false|null
	 */
	public function FilterBool(string $name) {
		if (!isset($this->values[$name])) {
			return null;
		}
		
		return filter_bool($this->values[$name]);
	}

	protected function Remove(string ...$name): self {
		foreach ($name as $n) {
			unset($this->values[$n]);
		}
		return $this;
	}

	protected function RemoveList(): self {
		throw new \Exception("");
	}
}