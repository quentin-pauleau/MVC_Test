<?php
namespace Modules\FormHandling;

class FormHandler
{

	private array $fields = [];

	public function __construct(
		array $fields,
	) {
		$this->fields = $fields;
	}
}