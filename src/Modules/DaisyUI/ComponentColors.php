<?php
namespace Modules\DaisyUI;

use Stringable;

enum ComponentColors : string implements Stringable
{
	case NEUTRAL = 'neutral';
	case PRIMARY = 'primary';
	case SECONDARY = 'secondary';
	case ACCENT = 'accent';
	case INFO = 'info';
	case SUCCESS = 'success';
	case WARNING = 'warning';
	case ERROR = 'error';

	public function __tostring(): string
	{
		return $this->value;
	}
}