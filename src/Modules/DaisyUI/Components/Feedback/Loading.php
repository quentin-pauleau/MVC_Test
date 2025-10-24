<?php
namespace Modules\DaisyUI;

use Modules\DaisyUI\Attributes\Component;
use Modules\DaisyUI\Enums\ComponentColors;
use Modules\DaisyUI\Enums\ComponentLoadingStyles;
use Modules\DaisyUI\Enums\ComponentSizes;
use Modules\DaisyUI\Traits\HasColor;
use Modules\DaisyUI\Traits\HasSize;
use Modules\HTMLElement\HTMLElement;

class Loading extends HTMLElement
{
	use HasColor;
	use HasSize;

	private ComponentLoadingStyles|null $style;


	/**
	 * Creates a new Loading animation
	 * @param \Modules\DaisyUI\Enums\ComponentLoadingStyles|null $style Style of the animation
	 * @param \Modules\DaisyUI\Enums\ComponentColors|null $color Color of the animation
	 * @param \Modules\DaisyUI\Enums\ComponentSizes|null $size Size of the animation
	 */
	public function __construct(
		ComponentLoadingStyles|null $style,
		ComponentColors|null $color,
		ComponentSizes|null $size,
	) {
		$this->style = $style;
		$this->color = $color;
		$this->size = $size;
	}

	public function __toString(): string
	{
		return "<span class=\"loading {$this->GetColorClass()}} {$this->GetSizeClass()} {$this->GetStyleClass()}\"></span>";
	}


	public function setStyle(ComponentLoadingStyles|null $style): static
	{
		$this->style = $style;
		return $this;
	}

	public function getStyle(): ComponentLoadingStyles|null
	{
		return $this->style;
	}


	private function GetColorClass(): string {
		return match($this->color) {
			null => '',
			ComponentColors::PRIMARY => 'progress-primary',
			ComponentColors::SECONDARY => 'progress-secondary',
			ComponentColors::ACCENT => 'progress-accent',
			ComponentColors::NEUTRAL => 'progress-neutral',

			ComponentColors::INFO => 'progress-info',
			ComponentColors::SUCCESS => 'progress-success',
			ComponentColors::WARNING => 'progress-warning',
			ComponentColors::ERROR => 'progress-error',
			default => throw new \Exception("Unsupported color : $this->color")
		};
	}

	private function GetSizeClass(): string {
		return match ($this->size) {
			null => '',
			ComponentSizes::EXTRA_SMALL => 'loading-xs',
			ComponentSizes::SMALL => 'loading-sm',
			ComponentSizes::MEDIUM => 'loading-md',
			ComponentSizes::LARGE => 'loading-lg',
			ComponentSizes::EXTRA_LARGE => 'loading-xl',
			default => throw new \Exception("Unsupported size : $this->size")
		};
	}

	private function GetStyleClass(): string {
		return match ($this->style) {
			null => '',
			ComponentLoadingStyles::SPINNER => 'loading-spinner',
			ComponentLoadingStyles::DOTS => 'loading-dots',
			ComponentLoadingStyles::RING => 'loading-ring',
			ComponentLoadingStyles::BALL => 'loading-ball',
			ComponentLoadingStyles::BARS => 'loading-bars',
			ComponentLoadingStyles::INFINITY => 'loading-infinity',
			default => throw new \Exception("Unsupported loading style : $this->style")
		};
	}


}