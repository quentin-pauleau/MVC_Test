<?php
namespace Modules\HTMLElement;

use Stringable;

abstract class HTMLElement implements Stringable
{
	use HasId;

	/**
	 * @param mixed $id
	 */
	public function __construct(
		string|null $id = null,
	) {
		$this->id = $id;
	}

	/**
	 * Display the component on the page
	 * @return void
	 */
	final public function display(): void {
		echo $this;
	}

	abstract public function __toString(): string;
}