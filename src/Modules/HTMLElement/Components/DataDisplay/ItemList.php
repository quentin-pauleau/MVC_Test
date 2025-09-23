<?php
namespace Modules\HTMLElement\Components\DataDisplay;

use Modules\HTMLElement\HTMLElement;


/**
 * List is a vertical layout to display information in rows.
 */
class ItemList extends HTMLElement
{

	/**
	 * @param ItemListRow[] $items
	 * @param mixed $id
	 */
	public function __construct(array $items, ?string $id = null)
	{
		$this->childs = $items;
		$this->id = $id;
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
}