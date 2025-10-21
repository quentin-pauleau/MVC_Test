<?php
namespace Modules\FormHandling;



class SelectHandler extends InputFieldHandler
{
	private bool $allowMultiple = false;
	private ?array $allowedValues;


	public function SetAllowedValues(mixed ...$allowedValues): static
	{
		$this->allowedValues = $allowedValues;
		return $this;
	}

	public function AddPossibleValue(mixed ...$possibleValue): static
	{
		array_push($this->allowedValues, ...$possibleValue);
		return $this;
	}


	public function IsAllowingMultiple(): bool
	{
		return $this->allowMultiple;
	}

	public function AllowsValue(mixed $value): bool
	{
		return in_array($value, $this->allowedValues);
	}


	/**
	 * 
	 * @param array $data
	 * @return array
	 */
	public function getValue(array $data): mixed
	{
		return parent::getValue($data);
	}

	public function GetAllowedValues(): array
	{
		return $this->allowedValues;
	}

	public function verify(array $data): static
	{
		$value = $this->getValue($data);

		$this->verifyRequired($value);
		$this->verifyConditions($value);

		$this->isVerified = true;
		return $this;
	}

	/**
	 * Verify is the given value matches one of the allowed values
	 * @param mixed $value
	 * @return bool
	 */
	public function verifyAllowedValues(mixed $value): bool
	{
		if ($this->allowedValues === null)
			return true;

		if ($this->allowsValue($value))
			return true;
		
		$this->addError($this->regexErrorMessage ?? "Value does not match the requirements");
		return false;
	}

}