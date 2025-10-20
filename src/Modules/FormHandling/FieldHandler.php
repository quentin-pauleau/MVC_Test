<?php
namespace Modules\FormHandling;

class FieldHandler
{
	private string $name;

	public function __construct()
	{}



	public function getName():string
	{
		return $this->name;
	}

	public function setName(string $name): static
	{
		$this->name = $name;
		return $this;
	}
}