<?php
namespace Modules\HTMLElement\Components;

enum ComponentColors : string
{
	case NEUTRAL = 'neutral';
	case PRIMARY = 'primary';
	case SECONDARY = 'secondary';
	case ACCENT = 'accent';
	case INFO = 'info';
	case SUCCESS = 'success';
	case WARNING = 'warning';
	case ERROR = 'error';
}