<?php

enum ComponentDirections : string implements Stringable
{
	case DEFAULT = '';
	case START = 'start';
	case END = 'end';
	case TOP = 'top';
	case BOTTOM = 'bottom';
	

	public function __tostring(): string
	{
		return $this->value;
	}
}