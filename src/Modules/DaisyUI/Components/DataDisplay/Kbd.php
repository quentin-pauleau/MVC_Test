<?php
namespace Modules\DaisyUI\Components\DataDisplay;

use Modules\DaisyUI\Enums\ComponentSizes;
use Modules\HTMLElement\HTMLElement;


class Kbd extends HTMLElement
{

	protected ComponentSizes|null $size = null;

	protected string $text;

	/**
	 * 
	 * @param mixed $id
	 * @param ComponentSizes|null $size
	 * @param bool $hasBorder
	 */
	public function __construct(
		string $text,
		ComponentSizes|null $size = null,
	)
	{
		$this->text = $text;
		$this->size = $size;
	}


	public function __toString(): string
	{
		return <<<HTML
		<kbd id="{$this->getId()}" class="kbd {$this->getSizeClass()}">{$this->text}</kbd>
		HTML;
	}


	protected function getSizeClass(): string
	{
		return match ($this->size) {
			ComponentSizes::EXTRA_SMALL => 'kbd-xs',
			ComponentSizes::SMALL => 'kbd-sm',
			ComponentSizes::MEDIUM => 'kbd-md',
			ComponentSizes::LARGE => 'kbd-lg',
			ComponentSizes::EXTRA_LARGE => 'kbd-xl',
			default => '',
		};
	}

	public function setSize(ComponentSizes|null $size): static
	{
		$this->size = $size;
		return $this;
	}


	public function setText(string $text): static
	{
		$this->text = $text;
		return $this;
	}

	public function getText(): string
	{
		return $this->text;
	}


	public static function CreateXSmall(string $text): static { return new static ($text, ComponentSizes::EXTRA_SMALL); }
	public static function CreateSmall(string $text): static { return new static ($text, ComponentSizes::SMALL); }
	public static function CreateMedium(string $text): static { return new static ($text, ComponentSizes::MEDIUM); }
	public static function CreateLarge(string $text): static { return new static ($text, ComponentSizes::LARGE); }
	public static function CreateXLarge(string $text): static { return new static ($text, ComponentSizes::EXTRA_LARGE); }
}