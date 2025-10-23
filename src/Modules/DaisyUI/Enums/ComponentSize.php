<?php
namespace Modules\DaisyUI\Enums;

use Stringable;

enum ComponentSize : string implements Stringable
{
	case DEFAULT = '';

	case EXTRA_SMALL = 'xs';
	case SMALL = 'sm';
	case MEDIUM = 'md';
	case LARGE = 'lg';
	case EXTRA_LARGE = 'xl';

	public function __tostring(): string
	{
		return $this->value;
	}
}