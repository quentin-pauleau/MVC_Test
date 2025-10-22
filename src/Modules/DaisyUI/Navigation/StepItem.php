<?php
namespace Modules\DaisyUI\Navigation;

use Modules\DaisyUI\ComponentColors;
use Modules\HTMLElement\HTMLElement;

/**
 * Step UI component, based on the DaisyUI library.
 * 
 * Step item is intended to be used with the Steps component.
 * It represents a single step in the navigation process.
 *
 * @see https://daisyui.com/components/steps/
 *
 * @package Modules\DaisyUI\Navigation
 */
class StepItem extends HTMLElement
{
	private string $label;
	private string $icon;
	private ComponentColors $color;


	/**
	 * @param string|null $id
	 * @param string $label label of step
	 * @param string|null $icon icon of step
	 * @param ComponentColors $color color of step
	 */
	public function __construct(
		string|null $id,
		string $label,
		string|null $icon = null,
		ComponentColors $color = ComponentColors::PRIMARY,
	) {
		$this->setId($id);
		$this->label = $label;
		$this->icon = $icon;
		$this->color = $color;
	}


	public function __toString() :string {
		return "<li class=\"step {$this->GetColorClass()}\"><li class=\"step\">";
	}

	public function setColor(ComponentColors $color): self
	{
		$this->color = $color;
		return $this;
	}

	public function setLabel(string $label): self
	{
		$this->label = $label;
		return $this;
	}

	public function setIcon(string $icon): self
	{
		$this->icon = $icon;
		return $this;
	}


	public function getLabel(): string
	{
		return $this->label;
	}

	public function getIcon(): string
	{
		return $this->icon;
	}

	public function getColor(): ComponentColors
	{
		return $this->color;
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

	protected function getContent(): string
	{
		return ($this->icon === null) ? $this->label : "<span class=\"step-icon\">{$this->icon}</span>{$this->label}";
	}
}