<?php
namespace Modules\DaisyUI\Traits;

use Modules\DaisyUI\Enums\ComponentColors;

trait HasColor
{
	private ComponentColors|null $color;


	public function getColor(): ComponentColors|null
	{
		return $this->color;
	}

	public function setColor(ComponentColors|null $color): static
	{
		$this->color = $color;
		return $this;
	}
}