<?php
namespace Modules\DaisyUI\DataDisplay;

use Modules\HTMLElement\HTMLElement;



class Avatar extends HTMLElement
{
	protected string|null $url;

	protected string $placeholder = '';

	protected int $size;

	/**
	 * @var "circle"|"square"|"rounded"|"heart"|"hexagon"|"" $shape
	 */
	protected string $shape;

	/**
	 * Presence indicator is a small dot that appears on the top right corner of the avatar.
	 * It indicates whether the user is online or offline.
	 * 
	 * null, removes the presence indicator.
	 * @var bool|null
	 */
	protected bool|null $presenceIndicator = null;


	/**
	 * Summary of __construct
	 * @param ?string $id
	 * @param string $url
	 * @param int $size
	 * @param "circle"|"square"|"rounded"|"heart"|"hexagon"|"" $shape
	 * @param bool|null $presenceIndicator
	 */
	public function __construct(
		?string $id = null, 
		string $url, 
		int $size = 24,
		string $shape = 'circle',
		bool|null $presenceIndicator = null
	)
	{
		$this->id = $id;
		$this->url = $url;
		$this->size = $size;
		$this->shape = $shape;
		$this->presenceIndicator = $presenceIndicator;
	}

	public function __toString(): string
	{
		return $this->url !== null ? $this->getWithImage() : $this->getWithoutImage();
	}

	protected function getWithImage(): string {
		return <<<HTML
		<div class="avatar {$this->getPresenceIndicatorClass()}">
			<div class="{$this->getSizeClass()} {$this->getShapeClass()} ">
				<img src="{$this->url}" alt="{$this->placeholder}" />
			</div>
		</div>
		HTML;
	}


	protected function getWithoutImage(): string {
		return <<<HTML
		<div class="avatar avatar-placeholder {$this->getPresenceIndicatorClass()}">
			<div class="{$this->getSizeClass()} {$this->getShapeClass()} bg-neutral text-neutral-content">
				<span>{$this->placeholder}</span>
			</div>
		</div>
		HTML;
	}


	protected function getSizeClass(): string
	{
		return "w-{$this->size}";
	}


	protected function getShapeClass(): string
	{
		return match($this->shape) {
			'circle' => 'rounded-xl',
			'square' => '',
			'rounded' => 'rounded-xl',
			'heart' => 'mask mask-heart',
			'hexagon' => 'mask mask-hexagon',
			default => 'rounded-xl'
		};
	}

	protected function getPresenceIndicatorClass(): string
	{
		return match($this->presenceIndicator) {
			null => '',
			true => 'avatar-online',
			false => 'avatar-offline',
		};
	}


	public function setUrl(string $url): self
	{
		$this->url = $url;
		return $this;
	}


	public function setPlaceholder(string $placeholder): self
	{
		$this->placeholder = $placeholder;
		return $this;
	}


	public function setSize(int $size): self
	{
		// if ($size < 1)
		// 	throw new \Exception("Size must be greater than zero");

		$this->size = $size;
		return $this;
	}

	
	/**
	 * 
	 * @param "circle"|"square"|"rounded"|"heart"|"hexagon"|"" $shape
	 */
	public function setShape(string $shape): self
	{
		$this->shape = $shape;
		return $this;
	}

	public function setPresenceIndicator(bool|null $presenceIndicator): self
	{
		$this->presenceIndicator = $presenceIndicator;
		return $this;
	}
}