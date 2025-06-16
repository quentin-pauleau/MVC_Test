<?php
namespace Components\FormComponents\InputComponents;

use Components\FormComponents\FormElementComponent;

abstract class InputComponent extends FormElementComponent
{
	public const DEFAULT_CLASS = "
		border border-gray-400 w-full rounded-lg
		p-8 text-5xl border-4
		lg:p-2 lg:text-base lg:border-2 
		".self::BASE_CLASS;

	public string $class = self::DEFAULT_CLASS;
	public bool $input_required = false;


	public function __construct(string $id, string $class,
			string $name = "", bool $is_required = false) {
		
		parent::__construct($id, $class, $name, $is_required);
	}


	abstract public static function Display(string $id, string $class, 
			string $name, bool $is_required = false): void;
}
