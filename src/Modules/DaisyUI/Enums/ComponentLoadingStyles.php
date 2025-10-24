<?php
namespace Modules\DaisyUI\Enums;

use Stringable;

enum ComponentLoadingStyles: string implements Stringable
{
	/**
	 * A spinning circle
	 */
	case SPINNER = 'spinner';

	/**
	 * Three dots moving up and down
	 */
	case DOTS = 'dots';

	/**
	 * A ring fading in and out as it grows bigger
	 */
	case RING = 'ring';

	/**
	 * A ball moving up and down
	 */
	case BALL = 'ball';

	/**
	 * Three bars fading in and out one after the others
	 */
	case BARS = 'bars';

	/**
	 * A spinning infinity character
	 */
	case INFINITY = 'infinity';

	public function __tostring() : string
	{
		return $this->value;
	}
}