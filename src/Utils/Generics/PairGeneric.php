<?php
namespace Utils\Generics;

/**
 * @template-contravariant string
 * @template-covariant T
 */
abstract class PairGeneric
{
	final public string $name;
	abstract public $value;


	abstract public function __construct(string $name);
}