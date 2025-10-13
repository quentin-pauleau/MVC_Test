<?php
namespace Modules\HTMLElement\Components\DataDisplay;

use Stringable;

enum ComponentShadows : string implements Stringable
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