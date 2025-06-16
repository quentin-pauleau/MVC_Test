<?php
namespace Utils\Requests;

use Utils\Generics\ManagerGeneric;
use Utils\Generics\PairGeneric;


/**
 * 
 */
abstract class RequestDataAbstract extends ManagerGeneric
{
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

	protected function Remove(): self {
		throw new \Exception("");
	}

	protected function RemoveList(): self {
		throw new \Exception("");
	}
}