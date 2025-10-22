<?php
namespace Modules\DaisyUI\DataDisplay;

use Modules\HTMLElement\HTMLElement;


/**
 * List is a vertical layout to display information in rows.
 */
class ItemList extends HTMLElement
{
	protected array $childs;

	/**
	 * @param ItemListRow[] $items
	 * @param mixed $id
	 */
	public function __construct(?string $id = null, array $items)
	{
		$this->id = $id;
		$this->childs = $items;
	}


	public function __toString(): string
	{
		$content = implode('', array_map(fn($item) => (string)$item, $this->childs));
		return <<<HTML
		<ul class="list">
			{$content}
		</ul>
		HTML;
	}


	public function getListRow(int $index): ?ItemListRow
	{
		if (!isset($this->childs[$index]))
			return null;

		return $this->childs[$index];
	}


	public function addRow(ItemListRow ...$row): self
	{
		array_push($this->childs, ...$row);
		return $this;
	}
}