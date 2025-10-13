<?php
namespace Modules\HTMLElement\Components\Layout;

use ComponentDirections;
use Modules\HTMLElement\HTMLElement;
use Modules\HTMLElement\HasChilds;



class Stack extends HTMLElement
{
	use HasChilds;
	protected ComponentDirections|null $Direction;
	protected int|null $height;
	protected int|null $width;

	/**
	 * @param string|null $id
	 * @param array<HTMLElement|string> $childs
	 * @param ComponentDirections|null $direction
	 * @param int|null $size Set both height and width
	 * @param int|null $height Height of elements in the stack
	 * @param int|null $width Width of elements in the stack
	 */
	public function __construct(
		string|null $id = null,
		array $childs = [],
		ComponentDirections|null $direction = null,
		int|null $size = null,
		int|null $height = null,
		int|null $width = null,
	) {
		parent::__construct($id);

		$this->setChild($childs);

		$this->Direction = $direction;
		$this->height = $size ?? $height;
		$this->width = $size ?? $width;
	}


	public function __toString(): string
	{
		return <<<HTML
		<button id="{$this->getId()}" class="stack {$this->getDirectionClass()} {$this->getHeightClass()}">
			{$this->getContent()}
		</button>
		HTML;
	}
	protected function getDirectionClass(): string
	{
		return match ($this->Direction) {
			ComponentDirections::TOP => 'stack-top',
			ComponentDirections::BOTTOM => 'stack-bottom',
			ComponentDirections::START => 'stack-start',
			ComponentDirections::END => 'stack-end',
			default => ''
		};
	}

	protected function getWidthClass(): string
	{
		return match ($this->width) {
			null => '',
			default => "w-{$this->width}"
		};
	}
	protected function getHeightClass(): string
	{
		return match ($this->width) {
			null => '',
			default => "h-{$this->height}"
		};
	}


	public function setHeight(int|null $height): static
	{
		$this->height = $height;
		return $this;
	}

	public function getHeight(): int|null
	{
		return $this->height;
	}


	public function setWidth(int|null $height): static
	{
		$this->height = $height;
		return $this;
	}

	public function getWidth(): int|null
	{
		return $this->height;
	}


	public function setDirection(ComponentDirections $direction): static
	{
		$this->Direction = $direction;
		return $this;
	}

	public function getDirection(): ComponentDirections|null
	{
		return $this->Direction;
	}

}