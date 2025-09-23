<?php
namespace Modules\HTMLElement\Components\DataDisplay;

use Modules\HTMLElement\HTMLElement;


/**
 * Row of an item list {@see ItemList}
 */
class ItemListRow extends HTMLElement
{
	protected ?int $wrappingIndex = null;
	protected int $growingIndex = 1;

	/**
	 * Summary of __construct
	 * @param HTMLElement[]|string[] $childs
	 * @param string $id
	 * @param ?int $wrappingIndex Child of the item that should be pushed to the next line
	 * @param int $growingIndex Child of the item that should be extended to fill the remaining space, by default the 2nd child (index = 1)
	 */
	public function __construct(
		array $childs = [], 
		?string $id = null,
		?int $wrappingIndex = null,
		int $growingIndex = 1,
	)
	{
		$this->childs = $childs;
		$this->id = $id;
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
		$content = '';
		for ($i = 0; $i < count($this->childs); $i++) {
			$content .= match ($i) {
				$this->wrappingIndex => <<<HTML
					<div class="list-col-wrap">{$this->childs[$i]}</div>
					HTML,

				$this->growingIndex => <<<HTML
					<div class="list-col-grow">{$this->childs[$i]}</div>
					HTML,
				
				default => $this->childs[$i],
			};
		}
		return $content;
	}

	public function getWrappingIndex(): ?int
	{
		return $this->wrappingIndex;
	}


	/**
	 * Set the child that should be pushed to the next line, null means no child will be pushed
	 * @param mixed $wrappingIndex
	 * @return ItemListRow
	 */
	public function setWrappingIndex(?int $wrappingIndex): self
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
	 * @return ItemListRow
	 */
	public function setGrowingIndex(int $growingIndex): self
	{
		$this->growingIndex = $growingIndex;
		return $this;
	}


	
	public function setColumn(int $index, HTMLElement|string $content): self
	{
		$this->childs[$index] = $content;
		return $this;
	}


	public function addColumn(HTMLElement|string $content): self
	{
		$this->childs[] = $content;
		return $this;
	}

}