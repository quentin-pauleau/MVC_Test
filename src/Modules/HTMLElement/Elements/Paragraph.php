<?php
namespace Modules\HTMLElement\Elements;

use Modules\HTMLElement\HTMLBaseElement;
use Modules\HTMLElement\HTMLElement;
use Modules\HTMLElement\HasChilds;

class Paragraph extends HTMLBaseElement
{
	use HasChilds;

	/**
	 * @param string $id
	 * @param HTMLElement|string[] $childs
	 */
	public function __construct(
		string|null $id = null,
		array|string $class = null,
		array $childs = [],
	) {
		parent::__construct($id, $class);
		$this->childs = $childs;
	}


	public function __toString(): string
	{
		return <<<HTML
		<p id="{$this->id}" class="{$this->getClassText()}">
			{$this->getContent()}
		</p>
		HTML;
	}


	public static function CreateText(string $text): Paragraph
	{
		return new Paragraph(null, [$text]);
	}
}