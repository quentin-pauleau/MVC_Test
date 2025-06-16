<?php
namespace Utils\Session;


final class SessionErrorCollection extends SessionAbstractCollection
{
	/**
	 * @var array<string, string|\Stringable>
	 */
	public array $values;
	
	private const ARRAY_VALUE = 'error';
	

	private function __construct(string $name) {
		$_SESSION[self::ARRAY_VALUE] ??= [];
	
		$_SESSION[self::ARRAY_VALUE][$name] ??= [];
	
		$this->values = $_SESSION[self::ARRAY_VALUE][$name];
	}

	
	public function Get(string $name): string {
		return $this->values[$name];
	}

	/**
	 * Summary of Add
	 * @param string $name
	 * @param string|\Stringable $value
	 * @return void
	 */
	public function Add(string $name, $value = ''): self {
		$this->values[$name];
		return $this;
	}



	public function current(): string {
		return $this->values[$this->current_name];
	}
}