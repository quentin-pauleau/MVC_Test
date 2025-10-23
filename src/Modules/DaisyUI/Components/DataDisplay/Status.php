<?php
namespace Modules\DaisyUI\Components\DataDisplay;

use Modules\DaisyUI\Attributes\Component;
use Modules\DaisyUI\Enums\ComponentSizes;
use Modules\DaisyUI\Enums\ComponentColors;
use Modules\DaisyUI\Traits\HasColor;
use Modules\DaisyUI\Traits\HasSize;
use Modules\HTMLElement\HasChilds;
use Modules\HTMLElement\HTMLElement;


class Status extends HTMLElement
{
	use HasSize;
	use HasColor;
	use HasChilds;

	/**
	 * @param ComponentColors|null $color
	 * @param ComponentSizes|null $size
	 * @param array<HTMLElement|string> $childs
	 */
	public function __construct(
		ComponentColors|null $color = null,
		ComponentSizes|null $size = null,
		array $childs = [],
	)
	{
		$this->color = $color;
		$this->size = $size;
		$this->setChild($childs);
	}


	public function __toString(): string
	{
		return <<<HTML
		<div id="{$this->getId()}" class="status {$this->getColorClass()} {$this->getSizeClass()}">
			{$this->getContent()}
		</div>
		HTML;
	}


	protected function getSizeClass(): string
	{
		return match ($this->size) {
			ComponentSizes::EXTRA_SMALL => 'status-xs',
			ComponentSizes::SMALL => 'status-sm',
			ComponentSizes::MEDIUM => 'status-md',
			ComponentSizes::LARGE => 'status-lg',
			ComponentSizes::EXTRA_LARGE => 'status-xl',
			default => '',
		};
	}



	protected function getColorClass(): string
	{
		return match ($this->color) {
			ComponentColors::PRIMARY => 'status-primary',
			ComponentColors::SECONDARY => 'status-secondary',
			ComponentColors::ACCENT => 'status-accent',
			ComponentColors::NEUTRAL => 'status-neutral',

			ComponentColors::SUCCESS => 'status-success',
			ComponentColors::INFO => 'status-info',
			ComponentColors::WARNING => 'status-warning',
			ComponentColors::ERROR => 'status-error',
			default => '',
		};
	}


	public static function CreateSimple(string $text, ComponentColors|null $color = null, ComponentSizes|null $size = null): static
	{
		return new static($color, $size, [$text]);
	}
}