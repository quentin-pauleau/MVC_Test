<?php
namespace Modules\DaisyUI\Navigation;

use Modules\HTMLElement\HasChilds;
use Modules\HTMLElement\HTMLElement;

/**
 * Breadcrumbs UI component, based on the DaisyUI library.
 * @see https://daisyui.com/components/breadcrumbs/
 *
 * @package Modules\DaisyUI\Navigation
 */
class Breadcrumbs extends HTMLElement
{
	use HasChilds;

	/**
	 * @param string|null $id
	 * @param array<HTMLElement|string> $childs
	 */
	public function __construct(
		string|null $id = null,
		array $childs = []
	) {
		parent::__construct($id);
	}


	public function __toString() :string {
		return <<<HTML
		<div id="{$this->getId()} "class="breadcrumbs">
			<ul>
				{$this->getContent()}
			</ul>
		</div>
		HTML;
	}

	public function getContent(): string {
		return implode('', array_map(fn($child) =>"<li>{$child}</li>", $this->childs));
	}
}