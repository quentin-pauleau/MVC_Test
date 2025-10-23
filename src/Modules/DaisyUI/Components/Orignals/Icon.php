<?php
namespace Modules\DaisyUI\Components;

use Modules\HTMLElement\HTMLElement;


class Icon extends HTMLElement
{
	public function __toString(): string
	{
		return 'icon';
	}
}