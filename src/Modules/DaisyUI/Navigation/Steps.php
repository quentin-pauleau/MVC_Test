<?php
namespace Modules\DaisyUI\Navigation;

use Modules\DaisyUI\ComponentColors;
use Modules\HTMLElement\HTMLElement;

/**
 * Steps UI component, based on the DaisyUI library.
 * @see https://daisyui.com/components/steps/
 * 
 * Represent a list of steps in a navigation process.
 * Steps are represented by the StepsItem class
 * 
 * @package Modules\DaisyUI\Navigation
 */
class Steps extends HTMLElement
{
	/**
	 * @var StepItem[]
	 */
	private array $steps;

	private bool $isDirectionHorizontal = false;

	/**
	 * @param string|null $id
	 * @param StepItem[] $steps different steps of navigation, where key is the icon and the value is the label, if the key isnt precised the step number is used instead
	 * @param ComponentColors $color color of step
	 * @param bool $isDirectionHorizontal if true, then horizontal direction will be used. Otherwise vertical direction is used
	 */
	public function __construct(
		string|null $id = null,
		array $steps,
		bool $isDirectionHorizontal = false,
	) {
		$this->setId($id);
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

	/**
	 * 
	 * @param StepItem[] $steps
	 * @return Steps
	 */
	public function setSteps(array $steps): self
	{
		$this->steps = $steps;
		return $this;
	}

	public function addSteps(StepItem ...$step): self
	{
		array_push($this->steps, ...$step);
		return $this;
	}

	public function removeSteps(int ...$index): self
	{
		foreach ($index as $i)
			unset($this->steps[$i]);
		
		return $this;
	}


	public function clearSteps(): self
	{
		$this->steps = [];
		return $this;
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


	protected function getDirectionClass(): string
	{
		return $this->isDirectionHorizontal ? 'steps-vertical' : 'steps-horizontal';
	}

	protected function getContent(): string
	{
		return implode("\n",$this->steps);
	}
}