<?php
namespace Modules\DaisyUI\Traits;

use Modules\DaisyUI\Enums\ComponentStyles;

trait HasStyle
{
	private ComponentStyles|null $style;


	public function getStyle(): ComponentStyles|null
	{
		return $this->style;
	}

	public function setStyle(ComponentStyles|null $style): static
	{
		$this->style = $style;
		return $this;
	}
}