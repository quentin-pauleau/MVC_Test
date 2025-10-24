<?php
namespace Modules\DaisyUI\Enums;

use Stringable;

enum ComponentPlacements: string implements Stringable
{
	case TOP_LEFT = 'top-left';
	case TOP = 'top';
	case TOP_RIGHT = 'top-right';
	case LEFT = 'left';
	case CENTER = 'center';
	case RIGHT = 'right';
	case BOTTOM_LEFT = 'bottom-left';
	case BOTTOM = 'bottom';
	case BOTTOM_RIGHT = 'bottom-right';


	public function __toString(): string
	{
		return $this->value;
	}
}