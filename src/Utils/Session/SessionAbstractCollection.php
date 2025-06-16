<?php
namespace Utils\Session;

use Utils\Generics\ManagerGeneric;
use Utils\Generics\PairGeneric;


/**
 * Object representation of $_SESSION
 * 
 * (Singleton) {@see Session::GetSession()} to get the instance of the session
 * 
 * @template-contravariant string
 * @template-covariant mixed|iterable
 */
abstract class SessionAbstractCollection extends ManagerGeneric
{
	/**
	 * Data stored in the session
	 * @var array<string, mixed|iterable>
	 */
	public array $values = [];
	
	protected function Remove(string ...$name): self {
		foreach ($name as $n) {
			unset($this->values[$n]);
		}
		return $this;
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
	
	final protected function AddManager(): self {
		throw new \Exception("");
	}

	final protected function AddPair(): self {
		throw new \Exception("");
	}

	final protected function GetPair(string $name): PairGeneric {
		throw new \Exception();
	}


	protected function RemoveList(): self {
		throw new \Exception("");
	}
}