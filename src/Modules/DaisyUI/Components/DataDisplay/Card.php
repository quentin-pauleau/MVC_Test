<?php
namespace Modules\DaisyUI\Components\DataDisplay;

use Modules\DaisyUI\Enums\ComponentSize;
use Modules\HTMLElement\HasChilds;
use Modules\HTMLElement\HTMLElement;


class Card extends HTMLElement
{
	use HasChilds;

	protected ComponentSize|null $size = null;

	protected bool $hasBorder = true;


	/**
	 * 
	 * @param mixed $id
	 * @param ComponentSize|null $size
	 * @param bool $hasBorder
	 * @param array<HTMLElement|string> $childs
	 */
	public function __construct(
		string|null $id = null,
		ComponentSize|null $size = null,
		bool $hasBorder = true,
		array $childs = []
	)
	{
		$this->setId($id);

		$this->size = $size;
		$this->hasBorder = $hasBorder;

		$this->setChild($childs);
	}


	public function __toString(): string
	{
		$content = implode('', array_map(fn($item) => (string)$item, $this->childs));

		return <<<HTML
		<div id="{$this->getId()} class="card {$this->getSizeClass()} {$this->getBorderSizeClass()}">
			{$content}
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

	public function setSize(ComponentSize|null $size): static
	{
		$this->size = $size;
		return $this;
	}



	public function enableBorder(): static
	{
		$this->hasBorder = true;
		return $this;
	}

	public function disableBorder(): static
	{
		$this->hasBorder = false;
		return $this;
	}

	public function setBorder(bool $hasBorder): static
	{
		$this->hasBorder = $hasBorder;
		return $this;
	}

	public function hasBorder(): bool
	{
		return $this->hasBorder;
	}

	public static function CreateEmpty(): self {
		return new self;
	}
}