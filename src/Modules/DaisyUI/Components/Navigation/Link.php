<?php
namespace Modules\DaisyUI\Components\Navigation;

use Modules\DaisyUI\Enums\ComponentColors;
use Modules\HTMLElement\HTMLElement;

/**
 * Link UI component, based on the DaisyUI library.
 * @see https://daisyui.com/components/link/
 *
 * @package Modules\DaisyUI\Components\Navigation
 */
class Link extends HTMLElement
{
	private string $text;
	private ComponentColors $color;

	private bool $underlineOnHoverOnly = false;

	/**
	 * @param string|null $id
	 * @param string $text
	 */
	public function __construct(
		string|null $id = null,
		string $text,
		bool $underlineOnHoverOnly = false,
	) {
		$this->setId($id);
		$this->text = $text;
		$this->underlineOnHoverOnly = $underlineOnHoverOnly;
	}


	public function __toString() :string {
		return <<<HTML
		<a id="{$this->getId()}" class="link {$this->GetColorClass()} {$this->getUnderlineOnHoverOnlyClass()}">{$this->text}</a>
		HTML;
	}

	private function GetColorClass(): string
	{
		return match ($this->color) {
			ComponentColors::PRIMARY => 'link-primary',
			ComponentColors::SECONDARY => 'link-secondary',
			ComponentColors::ACCENT => 'link-accent',
			ComponentColors::INFO => 'link-info',
			ComponentColors::SUCCESS => 'link-success',
			ComponentColors::WARNING => 'link-warning',
			ComponentColors::ERROR => 'link-error',
			default => '',
		};
	}

	private function getUnderlineOnHoverOnlyClass(): string
	{
		return $this->underlineOnHoverOnly ? 'link-hover' : '';
	}
}