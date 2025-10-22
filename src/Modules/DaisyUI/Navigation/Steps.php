<?php
namespace Modules\DaisyUI\Navigation;

use Modules\DaisyUI\ComponentColors;
use Modules\HTMLElement\HTMLElement;

/**
 * Link UI component, based on the DaisyUI library.
 * @see https://daisyui.com/components/steps/
 *
 * @package Modules\DaisyUI\Navigation
 */
class Link extends HTMLElement
{
	private array $steps;
	private ComponentColors $color;

	private bool $isDirectionHorizontal = false;

	/**
	 * @param string|null $id
	 * @param array<string|int, string> $steps different steps of navigation, where key is the icon and the value is the label, if the key isnt precised the step number is used instead
	 * @param ComponentColors $color color of link
	 * @param bool $isDirectionHorizontal if true, then horizontal direction will be used. Otherwise vertical direction is used
	 */
	public function __construct(
		string|null $id = null,
		array $steps,
		ComponentColors $color = ComponentColors::PRIMARY,
		bool $isDirectionHorizontal = false,
	) {
		parent::__construct($id);
		$this->steps = $steps;
		$this->isDirectionHorizontal = $isDirectionHorizontal;
	}


	public function __toString() :string {
		return <<<HTML
		<ul id="{$this->getId()}" class="steps {$this->getDirectionClass()}">
			{$this->getContent()}
		</ul>
		HTML;
	}

	public function setColor(ComponentColors $color): self
	{
		$this->color = $color;
		return $this;
	}

	public function setDirection(bool $isDirectionHorizontal): self
	{
		$this->isDirectionHorizontal = $isDirectionHorizontal;
		return $this;
	}

	public function setDirectionHorizontal(): self
	{
		$this->isDirectionHorizontal = true;
		return $this;
	}

	public function setDirectionVertical(): self
	{
		$this->isDirectionHorizontal = false;
		return $this;
	}

	public function setSteps(array $steps): self
	{
		$this->steps = $steps;
		return $this;
	}

	public function addStep(string $icon, string $label): self
	{
		$this->steps[$icon] = $label;
		return $this;
	}

	public function removeStep(int|string $key): self
	{
		unset($this->steps[$key]);
		return $this;
	}

	public function clearSteps(): self
	{
		$this->steps = [];
		return $this;
	}


	public function getColor(): ComponentColors
	{
		return $this->color;
	}

	public function getSteps(): array
	{
		return $this->steps;
	}

	public function countSteps(): int
	{
		return count($this->steps);
	}

	public function hasSteps(): bool
	{
		return $this->steps !== [];
	}

	public function isHorizontal(): bool
	{
		return $this->isDirectionHorizontal;
	}

	public function isVertical(): bool
	{
		return !$this->isDirectionHorizontal;
	}


	protected function GetColorClass(): string
	{
		return match ($this->color) {
			ComponentColors::PRIMARY => 'step-primary',
			ComponentColors::SECONDARY => 'step-secondary',
			ComponentColors::ACCENT => 'step-accent',
			ComponentColors::INFO => 'step-info',
			ComponentColors::SUCCESS => 'step-success',
			ComponentColors::WARNING => 'step-warning',
			ComponentColors::ERROR => 'step-error',
			default => '',
		};
	}

	protected function getDirectionClass(): string
	{
		return $this->isDirectionHorizontal ? 'steps-vertical' : 'steps-horizontal';
	}

	protected function getContent(): string
	{
		return implode(
			"\n",
			array_map(
				function (string $icon, string $label): string {
					if (is_int($icon))
						return "<li class=\"step {$this->GetColorClass()}\">{$label}</li>";
					
					return "<li class=\"step {$this->GetColorClass()}\"><span class=\"step-icon\">{$icon}</span>{$label}<li class=\"step\">";
				},
				array_keys($this->steps),
				$this->steps
			)
		);
	}
}