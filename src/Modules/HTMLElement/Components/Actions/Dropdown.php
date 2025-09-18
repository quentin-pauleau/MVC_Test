<?php
namespace Modules\HTMLElement\Components\Actions;

use Modules\HTMLElement\Components\Component;
use Modules\HTMLElement\HTMLElement;
use Modules\HTMLElement\Managers\TabIndexManager;

class Dropdown extends Component
{
	public HTMLElement|string $buttonContent;

	protected int $zIndex = 1;

	protected int $tabIndex;


	/**
	 * The alignment of the dropdown, default 'start'
	 * @var 'start'|'center'|'end'|''
	 */
	protected string $alignment = 'start';

	/**
	 * The direction the dropdown opens, default 'bottom'
	 * @var 'top'|'bottom'|'left'|'right'|''
	 */
	protected string $direction = 'bottom';


	protected bool $isOpenningOnHover = false;
	protected bool $isForcedOpen = false;

	private function __construct(
		string|null $id = null, 
		array $childs = [],
		HTMLElement|string $buttonContent = '',
		int $zIndex = 1,
	) {
		parent::__construct($id, $childs);
		$this->buttonContent = $buttonContent;
		$this->zIndex = $zIndex;
	}


	public function __toString(): string
	{
		$content = implode('', array_map(fn($item) => (string)$item, $this->childs));

		$this->tabIndex = TabIndexManager::getManager()->getNewIndex();

		// $this->buttonContent = new HTMLElement('span', ['class' => 'button-content'], $content);

		return <<<HTML
		<div id="{$this->id}" class="dropdown {$this->getDirectionClass()} {$this->getAlignmentClass()}">
			<div tabindex="{$this->tabIndex}" role="button">
				{$this->buttonContent}
			</div>
			<div tabindex="{$this->tabIndex}" class="dropdown-content z-{$this->zIndex}">
				{$content}
			</div>
		</div>
		HTML;
	}


	protected function getAlignmentClass(): string
	{
		return match ($this->alignment) {
			'start' => 'dropdown-start',
			'center' => 'dropdown-center',
			'end' => 'dropdown-end',
			default => '',
		};
	}


	protected function getDirectionClass(): string
	{
		return match ($this->direction) {
			'bottom' => 'dropdown-bottom',
			'top' => 'dropdown-top',
			'left' => 'dropdown-left',
			'right' => 'dropdown-right',
			default => '',
		};
	}

	protected function getHoverClass(): string
	{
		return $this->isOpenningOnHover ? 'dropdown-hover' : '';
	}

	protected function getForcedOpenClass(): string
	{
		return $this->isForcedOpen ? 'dropdown-open' : '';
	}


	/**
	 * Set the alignment of the dropdown
	 * @param 'start'|'center'|'end'|'' $alignment
	 * @return Dropdown
	 */
	public function setAlignment(string $alignment): self
	{
		$this->alignment = $alignment;
		return $this;
	}

	/**
	 * Get the alignment of the dropdown
	 * @return 'start'|'center'|'end'|'' $alignment
	 */
	public function getAlignment(): string
	{
		return $this->alignment;
	}


	/**
	 * Set the direction the dropdown opens
	 * @param 'top'|'bottom'|'left'|'right'|'' $direction
	 * @return Dropdown
	 */
	public function setDirection(string $direction): self
	{
		$this->direction = $direction;
		return $this;
	}

	/**
	 * Get the direction the dropdown opens
	 * @return 'top'|'bottom'|'left'|'right'|'' $direction
	 */
	public function getDirection(): string
	{
		return $this->direction;
	}


	public function getTabIndex(): int
	{
		if (!isset($this->tabIndex))
			throw new \RuntimeException('Tab index is not set, the dropdown must be stringified before accessing the tab index');

		return $this->tabIndex;
	}
}