<?php
namespace Modules\DaisyUI\DataDisplay;

use Modules\HTMLElement\HTMLElement;
use Modules\HTMLElement\HasChilds;

use function PHPSTORM_META\map;

/**
 * Row of an item list {@see ItemList}
 */
class ItemListRow extends HTMLElement
{
	use HasChilds;

	protected ?int $wrappingIndex = null;
	protected int $growingIndex = 1;

	/**
	 * Summary of __construct
	 * @param string $id
	 * @param array<HTMLElement|string> $childs
	 * @param ?int $wrappingIndex Child of the item that should be pushed to the next line
	 * @param int $growingIndex Child of the item that should be extended to fill the remaining space, by default the 2nd child (index = 1)
	 */
	public function __construct(
		?string $id = null,
		array $childs = [], 
		?int $wrappingIndex = null,
		int $growingIndex = 1,
	)
	{
		$this->id = $id;
		$this->childs = $childs;
		$this->wrappingIndex = $wrappingIndex;
		$this->growingIndex = $growingIndex;
	}

	public function __toString(): string
	{
		return <<<HTML
		<li class="list">
			{$this->getItemCols()}
		</li>
		HTML;
	}


	public function getItemCols(): string
	{
		$cols = array_map(
			fn ($i): HTMLElement|string => match ($i)
			{
				$this->wrappingIndex => <<<HTML
					<div class="list-col-wrap">{$this->childs[$i]}</div>
					HTML,

				$this->growingIndex => <<<HTML
					<div class="list-col-grow">{$this->childs[$i]}</div>
					HTML,
				
				default => $this->childs[$i],
			},
			array_keys($this->childs),
		);
		return implode(
			"\n",
			$cols
		);
	}

	public function getWrappingIndex(): int|null
	{
		return $this->wrappingIndex;
	}


	/**
	 * Set the child that should be pushed to the next line, null means no child will be pushed
	 * @param mixed $wrappingIndex
	 * @return static
	 */
	public function setWrappingIndex(?int $wrappingIndex): static
	{
		$this->wrappingIndex = $wrappingIndex;
		return $this;
	}

	public function getGrowingIndex(): int
	{
		return $this->growingIndex;
	}

	/**
	 * Set the child that should be extended to fill the remaining space
	 * @param int $growingIndex
	 * @return static
	 */
	public function setGrowingIndex(int $growingIndex): static
	{
		$this->growingIndex = $growingIndex;
		return $this;
	}


	
	public function setColumn(int $index, HTMLElement|string $content): static
	{
		$this->childs[$index] = $content;
		return $this;
	}


	public function addColumn(HTMLElement|string $content): static
	{
		$this->childs[] = $content;
		return $this;
	}

}