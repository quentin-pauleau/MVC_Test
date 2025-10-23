<?php
namespace Modules\DaisyUI\Layout;

use Modules\DaisyUI\ComponentColors;
use Modules\HTMLElement\HTMLElement;
use Modules\HTMLElement\HasChilds;



/**
 * Divider UI component, based on the DaisyUI library.
 * 
 * Dividers its children vertically or horizontally.
 * @see https://daisyui.com/components/join/ <br/>
 */
class Divider extends HTMLElement
{
	use HasChilds;
	protected ComponentColors|null $Color;
	protected bool $isDirectionVertical = true;

	/**
	 * Position of content inside divider.
	 * @var "start"|"end"|null
	 */
	protected string|null $contentPosition;

	/**
	 * @param string|null $id
	 * @param array<HTMLElement|string> $childs
	 * @param ComponentColors|null $color
	 * 
	 */
	public function __construct(
		string|null $id = null,
		array $childs = [],
		ComponentColors|null $color = null,
		bool $isDirectionVertical = true,
		string|null $contentPosition = null,
	) {
		$this->setId($id);

		$this->setChild($childs);

		$this->Color = $color;
		$this->isDirectionVertical = $isDirectionVertical;
		$this->contentPosition = $contentPosition;
	}


	public function __toString(): string
	{
		return <<<HTML
		<button id="{$this->getId()}" class="divider {$this->getColorClass()} {$this->getDirectionClass()} {$this->getContentPositionClass()}">
			{$this->getContent()}
		</button>
		HTML;
	}
	protected function getColorClass(): string
	{
		return match ($this->Color) {
			ComponentColors::NEUTRAL => 'divider-neutral',
			ComponentColors::PRIMARY => 'divider-primary',
			ComponentColors::SECONDARY => 'divider-secondary',
			ComponentColors::ACCENT => 'divider-accent',
			ComponentColors::INFO => 'divider-info',
			ComponentColors::SUCCESS => 'divider-success',
			ComponentColors::WARNING => 'divider-warning',
			ComponentColors::ERROR => 'divider-error',
			default => ''
		};
	}

	protected function getDirectionClass(): string
	{
		return $this->isDirectionVertical ? '' : 'divider-horizontal';
	}
	protected function getContentPositionClass(): string
	{
		return match ($this->contentPosition) {
			'start' => 'divider-start',
			'end' => 'divider-end',
			default => '',
		};
	}
	

	public function setDirectionVertical(): static
	{
		$this->isDirectionVertical = true;
		return $this;
	}
	public function setDirectionHorizontal(): static
	{
		$this->isDirectionVertical = false;
		return $this;
	}

	public function isDirectionVertical(): bool
	{
		return $this->isDirectionVertical;
	}


	/**
	 * Set the position of the content inside divider.
	 * @param "start"|"end"|null $contentPosition
	 * @return Divider
	 */
	public function setContentPosition(string|null $contentPosition): static
	{
		$this->contentPosition = $contentPosition;
		return $this;
	}

	public function getContentPosition(): string|null
	{
		return $this->contentPosition;
	}


	public function setColor(ComponentColors $color): static
	{
		$this->Color = $color;
		return $this;
	}

	public function getColor(): ComponentColors|null
	{
		return $this->Color;
	}

}