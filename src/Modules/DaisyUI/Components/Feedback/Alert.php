<?php
namespace Modules\DaisyUI;

use Modules\DaisyUI\Enums\ComponentInfoTypes;
use Modules\DaisyUI\Traits\HasInfoType;
use Modules\HTMLElement\Elements\Span;
use Modules\HTMLElement\HasChilds;
use Modules\HTMLElement\HTMLElement;

class Alert extends HTMLElement
{
	use HasInfoType;
	use HasChilds;

	private const ICON_INFO = <<<HTML
	<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="h-6 w-6 shrink-0 stroke-current">
		<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
	</svg>
	HTML;
	private const ICON_SUCCESS = <<<HTML
	<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
		<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
	</svg>
	HTML;

	private const ICON_WARNING = <<<HTML
	<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
		<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
	</svg>
	HTML;

	private const ICON_ERROR = <<<HTML
	<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
		<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
	</svg>
	HTML;


	public bool $hasIcon = true;
	public string|null $style = null;


	/**
	 * @param HTMLElement[] $childs
	 * @param ComponentInfoTypes|null $type
	 * @param bool $hasIcon
	 * @param "soft"|"outline"|"dash"|"default" $style
	 */
	public function __construct(
		array $childs = [],
		ComponentInfoTypes|null $type = null,
		bool $hasIcon = true,
		string|null $style = 'default',
	) {
		$this->setChild($childs);
		$this->infoType = $type;
		$this->hasIcon = $hasIcon;
		$this->style = $style;
	}

	public function __toString(): string
	{
		return <<<HTML
		<div id="{$this->id}" role="alert" class="alert {$this->getStyleClass()} {$this->getTypeClass()}">
			{$this->GetTypeIcon()} {$this->GetContent()}
		</div>
		HTML;
	}

	public function setHasIcon(bool $hasIcon): static
	{
		$this->hasIcon = $hasIcon;
		return $this;
	}

	public function setType(ComponentInfoTypes|null $infoType): static
	{
		$this->infoType = $infoType;
		return $this;
	}

	private function GetTypeIcon(): string {
		if (!$this->hasIcon)
			return '';

		return match($this->infoType) {
			null => static::ICON_INFO,
			ComponentInfoTypes::INFO => static::ICON_INFO,
			ComponentInfoTypes::SUCCESS => static::ICON_SUCCESS,
			ComponentInfoTypes::WARNING => static::ICON_WARNING,
			ComponentInfoTypes::ERROR => static::ICON_ERROR,
			default => throw new \Exception("Unsupported info type : $this->infoType"),
		};
	}
	

	private function getStyleClass(): string {
		return match($this->style) {
			null => '',
			'soft' => 'alert-soft',
			'outline' => 'alert-outline',
			'dash' => 'alert-dashed',
			default => throw new \Exception("Unsupported style : $this->style"),
		};
	}

	private function getTypeClass(): string {
		return match($this->infoType) {
			null => '',
			ComponentInfoTypes::INFO => 'alert-info',
			ComponentInfoTypes::SUCCESS => 'alert-success',
			ComponentInfoTypes::WARNING => 'alert-warning',
			ComponentInfoTypes::ERROR => 'alert-error',
			default => throw new \Exception("Unsupported info type : $this->infoType"),
		};
	}

	/**
	 * Change the type of alert.
	 * @param ComponentInfoTypes|null $type
	 * @return Alert
	 */
	public static function newSimple(ComponentInfoTypes|null $type, string $message): static {
		return new static(
			[
				new Span(
					childs: [
						$message
					],
				),
			],
			$type,
		);
	}
}