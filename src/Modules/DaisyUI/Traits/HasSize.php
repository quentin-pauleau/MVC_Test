<?php
namespace Modules\DaisyUI\Traits;

use Modules\DaisyUI\Enums\ComponentSizes;

trait HasSize
{
	private ComponentSizes|null $size;


	public function getSize(): ComponentSizes|null
	{
		return $this->size;
	}

	public function setSize(ComponentSizes|null $size): static
	{
		$this->size = $size;
		return $this;
	}
}