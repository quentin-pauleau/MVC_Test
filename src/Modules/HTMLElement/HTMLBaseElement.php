<?php
namespace Modules\HTMLElement;


abstract class HTMLBaseElement extends HTMLElement 
{
	/**
	 * Class own by the element
	 * @var string[]
	 */
	protected array $class = [];


	/**
	 * Id of the element
	 * @param mixed $id
	 */
	public function __construct(
		string|null $id = null,
		array|string $class = [],
	) {
		$this->id = $id;
		$this->class = $class;
	}


	public function getClassText(): string
	{
		return implode(' ', $this->class);
	}


	public function setClass(array|string $className): static
	{
		if (is_array($className))
			$this->class = $className;
		
		$this->class = explode(' ', $className);
		return $this;
	}

	public function addClass(string ...$className): static
	{
		$this->class .= '';
		return $this;
	}
}