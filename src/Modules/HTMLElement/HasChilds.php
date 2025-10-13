<?php
namespace Modules\HTMLElement;

trait HasChilds
{
	/**
	 * @var array<HTMLElement|string>
	 */
	protected array $childs = [];

	protected function getContent(): string
	{
		return implode("\n", array_map(fn($item) => (string)$item, $this->childs));
	}

	/**
	 * 
	 * @param array<HTMLElement|string> $child
	 */
	final public function setChild(array $childs): static
	{
		$this->childs = $childs;
		return $this;
	}

	/**
	 * 
	 * @param array<HTMLElement|string> $child
	 */
	final public function addChild(HTMLElement|string ...$child): static
	{
		array_push($this->childs, ...$child);
		return $this;
	}
}