<?php
namespace Components\DropdownComponents;



abstract class AbstractDropdownElement
{
	public string $id = '';

	public function __construct(string $id = '') {
		$this->id = $id;
	}

	abstract public static function Display(): void;

	abstract public function Show(): void;
}