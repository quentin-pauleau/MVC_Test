<?php
namespace Modules\DaisyUI;

use Modules\DaisyUI\Attributes\Component;
use Modules\DaisyUI\Enums\ComponentColors;
use Modules\DaisyUI\Traits\HasColor;
use Modules\HTMLElement\HTMLElement;

class Progress extends HTMLElement
{
	use HasColor;

	public ?int $value = null;
	public int $maxValue = 100;
	public ?int $width = null;


	/**
	 * Create a new alert.
	 * @param int $length
	 * @param string $type
	 */
	public function __construct(
		?int $value = null,
		int $maxValue = 100,
		?int $width = null,
	) {
		$this->setValue($value);
		$this->setMaxValue($maxValue);
		$this->setWidth($width);
	}

	public function __toString(): string
	{
		return "<progress class=\"progress {$this->GetColorClass()}} {$this->GetWidthClass()}\" value=\"{$this->value}\" max=\"{$this->maxValue}\"></progress>";
	}


	public function setValue(?int $value): static
	{
		$this->value = $value;
		return $this;
	}


	public function setMaxValue(int $maxValue): static
	{
		$this->maxValue = $maxValue;
		return $this;
	}


	public function setWidth(?int $width): static
	{
		$this->width = $width;
		return $this;
	}
	

	private function GetWidthClass(): string {
		if ($this->width < 0)
			throw new \Exception('The width must be greater than or equal to zero.');

		return match($this->width) {
			null => '',
			default => "w-{$this->width}"
		};
	}

	private function GetColorClass(): string {
		return match($this->color) {
			ComponentColors::PRIMARY => 'progress-primary',
			ComponentColors::SECONDARY => 'progress-secondary',
			ComponentColors::ACCENT => 'progress-accent',
			ComponentColors::NEUTRAL => 'progress-neutral',

			ComponentColors::INFO => 'progress-info',
			ComponentColors::SUCCESS => 'progress-success',
			ComponentColors::WARNING => 'progress-warning',
			ComponentColors::ERROR => 'progress-error',
			default => ''
		};
	}
}