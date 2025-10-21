<?php
namespace Modules\FormHandling;


class FormHanderBuilder
{
	private array $fields = [];
	private FieldHandler $ActiveFieldHandler;

	public function __construct()
	{
		
	}


	public function AddFields(FieldHandler ...$Fields): static
	{
		foreach ($Fields as $Field) {
			if (isset($this->fields[$Field->GetName()]))
				throw new \Exception("Duplicate field name");

			$this->fields[$Field->GetName()] = $Field;
		}

		return $this;
	}

	public function NewField(string $Name, string $Type): static
	{
		

		return $this;
	}
}