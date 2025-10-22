<?php
namespace Modules\DaisyUI\Layout;

use ComponentDirections;
use Modules\HTMLElement\HTMLElement;
use Modules\HTMLElement\HasChilds;


/**
 * Join UI component, based on the DaisyUI library.
 * 
 * Joins its child together horizontally or vertically.
 * @see https://daisyui.com/components/join/ <br/>
 * 
 * Unlike the original concept, this class will join all childs are joined together. <br/>
 * There are no exceptions !!!
 * 
 * @experimental This class is experimental and is intended to be change in future versions <br/>
 * As of now, this class will join all childs are joined together, unlike the original concept that only join defined elements.
 * 
 * @package Modules\DaisyUI\Navigation
 */
class Join extends HTMLElement
{
	use HasChilds;
	private bool $isDirectionVertical = false;

	/**
	 * @param string|null $id
	 * @param array<HTMLElement|string> $childs
	 * @param bool $isDirectionVertical
	 */
	public function __construct(
		array $childs = [],
		bool $isDirectionVertical = false,
	) {
		$this->setChild($childs);

		$this->isDirectionVertical = $isDirectionVertical;
	}


	public function __toString(): string
	{
		return <<<HTML
		<button id="{$this->getId()}" class="join {$this->getDirectionClass()}">
			{$this->getContent()}
		</button>
		HTML;
	}

	protected function getContent(): string
	{
		return implode(
			'', 
			array_map(
				fn ($child): string => "<div class=\"join-item\">{$child}</div>",
				$this->childs
			)
		);
	}


	protected function getDirectionClass(): string
	{
		return $this->isDirectionVertical ? 'vertical' : '';
	}


	public function isDirectionVertical(): bool
	{
		return $this->isDirectionVertical;
	}

	public function setDirectionVertical(): static
	{
		$this->isDirectionVertical = true;
		return $this;
	}

	public function setDirectionHorizontal(): static
	{
		$this->isDirectionVertical = true;
		return $this;
	}

}