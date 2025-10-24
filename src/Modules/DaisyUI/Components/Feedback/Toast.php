<?php
namespace Modules\DaisyUI;

use Modules\DaisyUI\Enums\ComponentPlacements;
use Modules\DaisyUI\Traits\HasPlacement;
use Modules\HTMLElement\HasChilds;
use Modules\HTMLElement\HTMLElement;

class Toast extends HTMLElement
{
	use HasChilds;
	use HasPlacement;


	public function __construct(
		ComponentPlacements|null $placement = null,
		array $childs = [],
	)
	{
		$this->setPlacement($placement);
		$this->setChild($childs);
	}


	public function __toString(): string
	{
		return <<<HTML
		<div class="toast {$this->GetPlacementClass()}">
			{$this->getContent()}
		</div>
		HTML;
	}

	
	public function GetPlacementClass(): string
	{
		return match ($this->getPlacement()) {
			null => '',
			ComponentPlacements::TOP_LEFT => 'toast-start toast-top',
			ComponentPlacements::TOP => 'toast-center toast-top',
			ComponentPlacements::TOP_RIGHT => 'toast-end toast-top',
			ComponentPlacements::LEFT => 'toast-start toast-middle',
			ComponentPlacements::CENTER => 'toast-center toast-middle',
			ComponentPlacements::RIGHT => 'toast-end toast-middle',
			ComponentPlacements::BOTTOM_LEFT => 'toast-start toast-bottom',
			ComponentPlacements::BOTTOM => 'toast-center toast-bottom',
			ComponentPlacements::BOTTOM_RIGHT => 'toast-end toast-bottom',
			default => throw new \Exception("Unsupported placement '{$this->getPlacement()}' for toast component"),
		};
	}
}