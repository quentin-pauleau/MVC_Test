<?php
namespace Modules\DaisyUI\Enums;

use Stringable;

enum ComponentStyles : string implements Stringable
{
	case OUTLINE = 'outline';
	case DASH = 'dash';
	case SOFT = 'soft';
	case GHOST = 'ghost';

	public function __tostring(): string
	{
		return $this->value;
	}
}