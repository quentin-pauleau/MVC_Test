<?php
namespace Components\FormComponents;


abstract class FormElementComponent 
{
	protected const BASE_CLASS = "
			border-4 outline-none outline-offset-2 
			lg:p-2 lg:text-base lg:border-2 
			hover:outline-indigo-400 hover:outline-2 hover:bg-indigo-100
			focus:border-blue-400 focus:bg-blue-50 focus:hover:outline-none 
			valid:border-emerald-400 valid:bg-emerald-50 
			required:invalid:border-red-400 required:invalid:bg-red-50 
			disabled:hover:outline-none disabled:bg-gray-200
			";

	public string $id;
	public string $class = "";
	public string $name;
	public bool $is_required = false;

	public function __construct(string $id, string $class, 
		string $name, bool $is_required = false)
	{
		$this->id = $id;
		$this->class = $class;
		$this->$name = $name;
		$this->$is_required = $is_required;
	}


	abstract public static function Display(string $id, string $class, 
			string $name, bool $is_required = false) : void;
}