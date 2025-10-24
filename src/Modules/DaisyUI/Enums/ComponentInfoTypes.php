<?php

namespace Modules\DaisyUI\Enums;

use Stringable;

enum ComponentInfoTypes : string implements Stringable
{
	case INFO = 'info';
	case SUCCESS = 'success';
	case WARNING = 'warning';
	case ERROR = 'error';


	public function __toString(): string
	{
		return $this->value;
	}
}