<?php
namespace Modules\HTMLElement\Components;

use Modules\HTMLElement\Elements\Span;
use Modules\HTMLElement\HTMLElement;
use Utils\UUID;

class Alert extends HTMLElement
{
	private const ICON_INFO = <<<HTML
	<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="h-6 w-6 shrink-0 stroke-current">
		<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
	</svg>
	HTML;
	private const ICON_SUCESS = <<<HTML
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


	/**
	 * @var "info"|"i"|"sucess"|"s"|"warning"|"w"|"error"|"e"|null
	 */
	public string $type = 'default';
	public bool $hasIcon = true;
	public string $style = 'default';


	/**
	 * @param string|null $id
	 * @param HTMLElement[] $childs
	 * @param "info"|"i"|"sucess"|"s"|"warning"|"w"|"error"|"e"|"default" $type
	 * @param bool $hasIcon
	 * @param "soft"|"outline"|"dash"|"default" $style
	 */
	public function __construct(
		string|null $id = null,
		array $childs = [],
		string $type = 'default',
		bool $hasIcon = true,
		string $style = 'default',
	) {
		$this->childs = $childs;
		$this->id = $id ?? UUID::Generate();
		$this->type = $type;
		$this->hasIcon = $hasIcon;
		$this->style = $style;
	}

	public function __toString(): string
	{
		$content = '';
		if ($this->hasIcon)
			$content .= match($this->type) {
				'info', 'i' => self::ICON_INFO,
				'sucess', 's' => self::ICON_SUCESS,
				'warning', 'w' => self::ICON_WARNING,
				'error', 'e' => self::ICON_ERROR,
				default => self::ICON_INFO
			};

		foreach ($this->childs as $child)
			$content .= (string)$child;

		return <<<HTML
		<div id="{$this->id}" role="alert" class="alert alert-{$this->type}">
			{$content}
		</div>
		HTML;
	}

	public function setHasIcon(bool $hasIcon): self
	{
		$this->hasIcon = $hasIcon;
		return $this;
	}
	

	public static function CreateDefault(string $type, string $message): self {
		return new self(
			childs: [
				new Span(
					childs: [
						$message
					],
				),
			],
			type: $type,
		);
		// <div role="alert" class="alert alert-info">
		// 	
		// 	<span>New software update available.</span>
		// </div>
	}
}