<?php
namespace Modules\HTMLElement\Elements;

use Modules\HTMLElement\HTMLElement;

class Span extends HTMLElement
{

	/**
	 * @param string $id
	 * @param HTMLElement|string[] $childs
	 */
	public function __construct(
		string|null $id = null,
		array $childs = [],
	) {
		$this->id = $id;
		$this->childs = $childs;
	}


	public function __toString(): string
	{
		$content = implode('', array_map(fn($item) => (string)$item, $this->childs));

		return <<<HTML
		<span id="{$this->id}">
			$content
		</span>
		HTML;
	}
}