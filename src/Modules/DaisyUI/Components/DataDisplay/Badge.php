<?php
namespace Modules\DaisyUI\Components\DataDisplay;

use Modules\DaisyUI\Attributes\Component;
use Modules\DaisyUI\Enums\ComponentSizes;
use Modules\DaisyUI\Enums\ComponentColors;
use Modules\DaisyUI\Enums\ComponentStyles;
use Modules\DaisyUI\Traits\HasColor;
use Modules\DaisyUI\Traits\HasSize;
use Modules\DaisyUI\Traits\HasStyle;
use Modules\HTMLElement\HasChilds;
use Modules\HTMLElement\HTMLElement;


class Badge extends HTMLElement
{
	use HasSize;
	use HasColor;
	use HasStyle;


	public const INFO_ICON = <<<HTML
	<svg class="size-[1em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
		<g fill="currentColor" stroke-linejoin="miter" stroke-linecap="butt">
			<circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-linecap="square" stroke-miterlimit="10" stroke-width="2"></circle>
			<polyline points="7 13 10 16 17 8" fill="none" stroke="currentColor" stroke-linecap="square" stroke-miterlimit="10" stroke-width="2"></polyline>
		</g>
	</svg>
	HTML;

	public const SUCCESS_ICON = <<<HTML
	<svg class="size-[1em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
		<g fill="currentColor" stroke-linejoin="miter" stroke-linecap="butt">
			<circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-linecap="square" stroke-miterlimit="10" stroke-width="2"></circle>
			<path d="m12,17v-5.5c0-.276-.224-.5-.5-.5h-1.5" fill="none" stroke="currentColor" stroke-linecap="square" stroke-miterlimit="10" stroke-width="2"></path>
			<circle cx="12" cy="7.25" r="1.25" fill="currentColor" stroke-width="2"></circle>
		</g>
	</svg>
	HTML;

	public const ERROR_ICON = <<<HTML
	<svg class="size-[1em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
		<g fill="currentColor">
			<rect x="1.972" y="11" width="20.056" height="2" transform="translate(-4.971 12) rotate(-45)" fill="currentColor" stroke-width="0"></rect>
			<path d="m12,23c-6.065,0-11-4.935-11-11S5.935,1,12,1s11,4.935,11,11-4.935,11-11,11Zm0-20C7.038,3,3,7.037,3,12s4.038,9,9,9,9-4.037,9-9S16.962,3,12,3Z" stroke-width="0" fill="currentColor"></path>
		</g>
	</svg>
	HTML;

	public const WARNING_ICON = <<<HTML
	<svg class="size-[1em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18">
		<g fill="currentColor">
			<path d="M7.638,3.495L2.213,12.891c-.605,1.048,.151,2.359,1.362,2.359H14.425c1.211,0,1.967-1.31,1.362-2.359L10.362,3.495c-.605-1.048-2.119-1.048-2.724,0Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></path>
			<line x1="9" y1="6.5" x2="9" y2="10" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></line>
			<path d="M9,13.569c-.552,0-1-.449-1-1s.448-1,1-1,1,.449,1,1-.448,1-1,1Z" fill="currentColor" data-stroke="none" stroke="none"></path>
		</g>
	</svg>
	HTML;


	private string $text;

	/**
	 * @param ComponentColors|null $color
	 * @param ComponentSizes|null $size
	 * @param ComponentStyles|null $style
	 * @param array<HTMLElement|string> $childs
	 */
	public function __construct(
		string $text,
		ComponentColors|null $color = null,
		ComponentSizes|null $size = null,
		ComponentStyles|null $style = null,
	) {
		$this->text = $text;
		$this->color = $color;
		$this->size = $size;
		$this->style = $style;
	}


	public function __toString(): string
	{
		return <<<HTML
		<div id="{$this->getId()}" class="badge {$this->getColorClass()} {$this->getSizeClass()}">
			{$this->text}
		</div>
		HTML;
	}


	protected function getSizeClass(): string
	{
		return match ($this->size) {
			ComponentSizes::EXTRA_SMALL => 'badge-xs',
			ComponentSizes::SMALL => 'badge-sm',
			ComponentSizes::MEDIUM => 'badge-md',
			ComponentSizes::LARGE => 'badge-lg',
			ComponentSizes::EXTRA_LARGE => 'badge-xl',
			default => '',
		};
	}



	protected function getColorClass(): string
	{
		return match ($this->color) {
			ComponentColors::PRIMARY => 'badge-primary',
			ComponentColors::SECONDARY => 'badge-secondary',
			ComponentColors::ACCENT => 'badge-accent',
			ComponentColors::NEUTRAL => 'badge-neutral',

			ComponentColors::SUCCESS => 'badge-success',
			ComponentColors::INFO => 'badge-info',
			ComponentColors::WARNING => 'badge-warning',
			ComponentColors::ERROR => 'badge-error',
			default => '',
		};
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


	/**
	 * Creates an Info Badge with an icon next to the text
	 * @param string $text The text to be displayed in the badge next to , by default 'Info'.
	 * @param \Modules\DaisyUI\Enums\ComponentSizes|null $size The size of the badge.
	 * @param \Modules\DaisyUI\Enums\ComponentSizes|null $size
	 * @return Badge
	 */
	public static function CreateInfo(string $text = 'Info', ComponentSizes|null $size = null): static {
		return new static(
			static::INFO_ICON . " {$text}",
			ComponentColors::WARNING, 
			$size
		);
	}

	/**
	 * Creates an Success Badge with an icon next to the text
	 * @param string $text The text to be displayed in the badge next to , by default 'Success'.
	 * @param \Modules\DaisyUI\Enums\ComponentSizes|null $size The size of the badge.
	 * @return Badge
	 */
	public static function CreateSucess(string $text = 'Success', ComponentSizes|null $size = null): static {
		return new static(
			static::SUCCESS_ICON . " {$text}",
			ComponentColors::WARNING,
			$size
		);
	}

	/**
	 * Creates an Warning Badge with an icon next to the text
	 * @param string $text The text to be displayed in the badge next to , by default 'Warning'.
	 * @param \Modules\DaisyUI\Enums\ComponentSizes|null $size The size of the badge.
	 * @return Badge
	 */
	public static function CreateWarning(string $text = 'Warning', ComponentSizes|null $size = null): static {
		return new static(
			static::WARNING_ICON . " {$text}",
			ComponentColors::WARNING, 
			$size
		);
	}

	/**
	 * Creates an Error Badge with an icon to the text
	 * @param string $text The text to be displayed in the badge next to , by default 'Error'.
	 * @param \Modules\DaisyUI\Enums\ComponentSizes|null $size The size of the badge.
	 * @return Badge
	 */
	public static function CreateError(string $text = 'Error', ComponentSizes|null $size = null): static {
		return new static(
			static::ERROR_ICON . " {$text}",
			ComponentColors::WARNING,
			$size
		);
	}

	
	
	/**
	 * Creates an Info Badge with no text and only an icon
	 * @param \Modules\DaisyUI\Enums\ComponentSizes|null $size The size of the badge.
	 * @return Badge
	 */
	public static function CreateInfoIcon(ComponentSizes|null $size = null): static {
		return new static(static::INFO_ICON, ComponentColors::INFO, $size);
	}

	/**
	 * Creates an Success Badge with no text and only an icon
	 * @param \Modules\DaisyUI\Enums\ComponentSizes|null $size
	 * @return Badge
	 */
	public static function CreateSuccessIcon(ComponentSizes|null $size = null): static {
		return new static(static::SUCCESS_ICON, ComponentColors::SUCCESS, $size);
	}

	/**
	 * Creates an Warning Badge with no text and only an icon
	 * @param \Modules\DaisyUI\Enums\ComponentSizes|null $size
	 * @return Badge
	 */
	public static function CreateWarningIcon(ComponentSizes|null $size = null): static {
		return new static(static::WARNING_ICON, ComponentColors::WARNING, $size);
	}

	/**
	 * Creates an Error Badge with no text and only an icon
	 * @param \Modules\DaisyUI\Enums\ComponentSizes|null $size
	 * @return Badge
	 */
	public static function CreateErrorIcon(ComponentSizes|null $size = null): static {
		return new static(static::ERROR_ICON, ComponentColors::ERROR, $size);
	}

}