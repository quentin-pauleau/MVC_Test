<?php
namespace Modules\HTMLElement\Elements;

use Modules\HTMLElement\HTMLBaseElement;
use Modules\HTMLElement\HTMLElement;
use Modules\HTMLElement\HasChilds;

class Span extends HTMLBaseElement
{
	use HasChilds;

	/**
	 * @param string|null $id
	 * @param string|null $class
	 * @param array<HTMLElement|string> $childs
	 */
	public function __construct(
		string|null $id = null,
		string|null $class = null,
		array $childs = [],
	) {
		parent::__construct(
			$id,
			$class,
		);

		$this->setChild($childs);
	}


	public function __toString(): string
	{
		return <<<HTML
		<span id="{$this->id}" class="{$this->getClassText()}}">
			{$this->getContent()}
		</span>
		HTML;
	}
}