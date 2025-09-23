<?php
namespace Modules\HTMLElement;

use Stringable;

abstract class HTMLElement implements Stringable
{
	/**
	 * @var self[]|string[]
	 */
	protected array $childs = [];

	protected ?string $id = null;


	/**
	 * @param mixed $id
	 * @param self[]|string[] $childs
	 */
	public function __construct(
		?string $id = null,
		array $childs = []
	) {
		$this->id = $id;
		$this->childs = $childs;
	}


	final public function display(): void {
		echo $this;
	}

	abstract public function __toString(): string;
	
	
	public function getId(): ?string
	{
		return $this->id;
	}
	
	public function setId(?string $id): self
	{
		$this->id = $id;
		return $this;
	}

	
	
	final protected function addChild(HTMLElement ...$child): self
	{
		foreach ($child as $c)
			$this->childs[] = $c;
		
		return $this;
	}
}