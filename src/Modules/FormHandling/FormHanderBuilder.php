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

	public function NewField(string $Name, FormFieldTypes $Type): static
	{
		$this->ActiveFieldHandler = match ($Type) {
			FormFieldTypes::INPUT_TEXT,
			FormFieldTypes::INPUT_PASSWORD,
			FormFieldTypes::INPUT_EMAIL,
			FormFieldTypes::TEXTAREA,
				=> new InputTextHandler,

			FormFieldTypes::INPUT_NUMBER,
				=> new InputNumberHandler,
			
			FormFieldTypes::DATE_PICKER,
				=> new DatePickerHandler,
			
			FormFieldTypes::CHECKBOX,
			FormFieldTypes::MULTI_SELECT,
				=> new SelectHandler,

			FormFieldTypes::RADIO,
			FormFieldTypes::SELECT,
				=> new SelectHandler,
			
			default => throw new \Exception("Invalid form type"),
		};

		$this->ActiveFieldHandler->SetName($Name);

		$this->AddFields($this->ActiveFieldHandler);
		return $this;
	}


	public function NewInputText(string $Name, bool $isRequired = true): static
	{
		$this->ActiveFieldHandler = new InputTextHandler;

		$this->ActiveFieldHandler
			->SetName($Name)
			->SetIsRequired($isRequired)
		;

		$this->AddFields($this->ActiveFieldHandler);
		return $this;
	}

	public function NewInputPassword(string $Name): static
	{
		return $this->NewField($Name, FormFieldTypes::INPUT_PASSWORD);
	}

	public function NewInputEmail(string $Name): static
	{
		return $this->NewField($Name, FormFieldTypes::INPUT_EMAIL);
	}

	public function NewInputNumber(string $Name): static
	{
		return $this->NewField($Name, FormFieldTypes::INPUT_NUMBER);
	}


	public function Build(): FormHandler
	{
		return new FormHandler(
			$this->fields,
		);
	}
}