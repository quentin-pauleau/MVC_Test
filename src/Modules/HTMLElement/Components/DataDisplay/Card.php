<?php
namespace Modules\HTMLElement\Components\DataDisplay;

use Modules\HTMLElement\HTMLElement;


class Card extends HTMLElement
{
	protected ComponentSize $size = ComponentSize::DEFAULT;

	protected bool $hasBorder = true;



	public function __construct(
		?string $id = null,
		ComponentSize $size = ComponentSize::DEFAULT,
		bool $hasBorder = true
	)
	{
		$this->id = $id;
		$this->size = $size;
		$this->hasBorder = $hasBorder;
	}


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

	protected function getBorderSizeClass(): string
	{
		return $this->hasBorder ? 'card-border' : '';
	}

	public function setSize(ComponentSize $size): self
	{
		$this->size = $size;
		return $this;
	}



	public function enableBorder(): self
	{
		$this->hasBorder = true;
		return $this;
	}

	public function disableBorder(): self
	{
		$this->hasBorder = false;
		return $this;
	}

	public function setBorder(bool $hasBorder): self
	{
		$this->hasBorder = $hasBorder;
		return $this;
	}

	public function hasBorder(): bool
	{
		return $this->hasBorder;
	}

	public static function createSimple(): self {
		return new self();
	}
}