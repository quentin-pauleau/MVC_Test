<?php
namespace Modules\DaisyUI\Traits;

use Modules\DaisyUI\Enums\ComponentInfoTypes;

trait HasInfoType
{
	private ComponentInfoTypes|null $infoType;


	public function getInfoType(): ComponentInfoTypes|null
	{
		return $this->infoType;
	}

	public function setInfoType(ComponentInfoTypes|null $infoType): static
	{
		$this->infoType = $infoType;
		return $this;
	}
}