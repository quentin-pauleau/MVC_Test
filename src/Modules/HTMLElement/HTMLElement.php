<?php
namespace Modules\HTMLElement;

use Stringable;

abstract class HTMLElement implements Stringable
{
	/**
	 * @var self|string[]
	 */
	protected array $childs = [];

	protected ?string $id = null;


	final public function display(): void {
		echo $this;
	}

	abstract public function __toString(): string;

	
	final public function addChild(HTMLElement ...$child): self
	{
		foreach ($child as $c)
			$this->childs[] = $c;
		
		return $this;
	}
}