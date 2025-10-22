<?php
namespace Modules\HTMLElement;

use Stringable;

abstract class HTMLElement implements Stringable
{
	use HasId;

	/**
	 * Display the component on the page
	 * @return void
	 */
	final public function display(): void {
		echo $this;
	}

	abstract public function __toString(): string;
}