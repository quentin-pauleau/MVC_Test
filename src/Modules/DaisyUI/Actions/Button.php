<?php
namespace Modules\DaisyUI\DataDisplay;

use Modules\DaisyUI\ComponentColors;
use Modules\HTMLElement\HasChilds;
use Modules\HTMLElement\HTMLElement;



class Button extends HTMLElement
{
	use HasChilds;

	protected ComponentColors $Color = ComponentColors::NEUTRAL;
	protected ComponentSize $Size = ComponentSize::DEFAULT;

	/**
	 * @var "dash" | "soft" | "outlined" | "ghost" | "link" | null
	 */
	protected ?string $style = null;

	/**
	 * @var "wide" | "block" | "square" | "circle" | null
	 */
	protected ?string $shape = null;

	protected bool $isDisabled = false;


	/**
	 * @param string|null $id
	 * @param array<HTMLElement|string> $childs
	 */
	public function __construct(
		string|null $id = null,
		array $childs = [],
		ComponentColors $color = ComponentColors::NEUTRAL,
		ComponentSize $size = ComponentSize::DEFAULT,
		string|null $style = null,
		string|null $shape = null,
		bool $isDisabled = false
	) {
		$this->setId($id);

		$this->setChild($childs);

		$this->Color = $color;
		$this->Size = $size;
		$this->style = $style;
		$this->shape = $shape;
		$this->isDisabled = $isDisabled;
	}


	public function __toString(): string
	{
		$isDisabled = $this->isDisabled ? 'disabled' : '';

		return <<<HTML
		<button class="btn {$this->getColorClass()} {$this->getSizeClass()} {$this->getStyleClass()} {$this->getShapeClass()}" {$isDisabled}>
			{$this->getContent()}
		</button>
		HTML;
	}

	protected function getColorClass(): string
	{
		return match ($this->Color) {
			ComponentColors::PRIMARY => 'btn-primary',
			ComponentColors::SECONDARY => 'btn-secondary',
			ComponentColors::ACCENT => 'btn-accent',
			ComponentColors::INFO => 'btn-info',
			ComponentColors::SUCCESS => 'btn-success',
			ComponentColors::WARNING => 'btn-warning',
			ComponentColors::ERROR => 'btn-error',
			default => ''
		};
	}

	protected function getSizeClass(): string
	{
		return match ($this->Size) {
			ComponentSize::EXTRA_SMALL => 'btn-xs',
			ComponentSize::SMALL => 'btn-sm',
			ComponentSize::MEDIUM => 'btn-md',
			ComponentSize::LARGE => 'btn-lg',
			ComponentSize::EXTRA_LARGE => 'btn-xl',
			default => ''
		};
	}

	protected function getStyleClass(): string
	{
		return match ($this->style) {
			'dash' => 'btn-dash',
			'soft', 'outlined' => 'btn-soft',
			'ghost' => 'btn-ghost',
			'link' => 'btn-link',
			default => ''
		};
	}

	protected function getShapeClass(): string
	{
		return match ($this->shape) {
			'wide' => 'btn-wide',
			'block' => 'btn-block',
			'square' => 'btn-square',
			'circle' => 'btn-circle',
			default => ''
		};
	}

	/**
	 * Set the size of the button
	 * @param ComponentSize $size Size of the button. (xsmall, small, medium, large, xlarge)
	 * @return static
	 */
	public function setSize(ComponentSize $size): static
	{
		$this->Size = $size;
		return $this;
	}

	public function getSize(): ComponentSize
	{
		return $this->Size;
	}

	public function setColor(ComponentColors $color): static
	{
		$this->Color = $color;
		return $this;
	}

	public function getColor(): ComponentColors
	{
		return $this->Color;
	}


	/**
	 * Set the style of the button
	 * @param "dash" | "soft" | "outlined" | "ghost" | "link" | null $style
	 * @return static
	 */
	public function setStyle(?string $style): static
	{
		$this->style = $style;
		return $this;
	}

	/**
	 * Get the style of the button.
	 * @return "dash" | "soft" | "outlined" | "ghost" | "link" | null
	 */
	public function getStyle(): ?string
	{
		return $this->style;
	}


	/**
	 * Set the shape of the button
	 * @param "wide" | "block" | "square" | "circle" | null $shape
	 * @return Button
	 */
	public function setShape(?string $shape): static
	{
		$this->shape = $shape;
		return $this;
	}

	/**
	 * Get the shape of the button.
	 * @return "wide" | "block" | "square" | "circle" | null
	 */
	public function getShape(): ?string
	{
		return $this->shape;
	}
	

	public function enable(): static
	{
		$this->isDisabled = false;
		return $this;
	}

	public function disable(): static
	{
		$this->isDisabled = true;
		return $this;
	}

	public function isDisabled(): bool
	{
		return $this->isDisabled;
	}
}