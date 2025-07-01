<?php
namespace Modules\HTMLElement\Components\DataDisplay;

use Modules\HTMLElement\HTMLElement;


class Card extends HTMLElement
{
	protected ComponentSize $size;

	protected bool $hasBorder = true;



	public function __construct()
	{}


	public function __toString(): string
	{
		return <<<HTML
		<div class="card {$this->getSizeClass()}">

		</div>
		HTML;
	}


	protected function getSizeClass(): string
	{
		return match ($this->size) {
			ComponentSize::EXTRA_SMALL => 'card-xs',
			ComponentSize::SMALL => 'card-sm',
			ComponentSize::MEDIUM => 'card-md',
			ComponentSize::LARGE => 'card-lg',
			ComponentSize::EXTRA_LARGE => 'card-xl',
			default => '',
		};
	}

	protected function getBorderSize(): string
	{
		return $this->hasBorder ? 'card-border' : '';
	}

	public function setSize(ComponentSize $size): self
	{
		$this->size = $size;
		return $this;
	}


	public static function CreateSimple(): self {
		return new self();
	}
}