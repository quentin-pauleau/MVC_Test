<?php
namespace Components;

abstract class Component {
	public const DEFAULT_CLASS = "";
	public string $id = "";
	public string $class = self::DEFAULT_CLASS;

	public function __construct(string $id, string $class) {
		$this->id = $id;
		$this->class = $class;
	}

	abstract public static function Display(string $id, string $class): void;
}