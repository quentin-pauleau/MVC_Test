<?php
namespace Modules\DaisyUI\Traits;

use Modules\DaisyUI\Enums\ComponentPlacements;

trait HasPlacement
{
	private ComponentPlacements|null $placement;


	public function getPlacement(): ComponentPlacements|null
	{
		return $this->placement;
	}

	public function setPlacement(ComponentPlacements|null $placement): static
	{
		$this->placement = $placement;
		return $this;
	}
}