<?php
namespace Modules\FormHandling;



class InputTextHandler extends InputFieldHandler
{
	private bool $allowMultiple = false;
	private ?array $possibleValues;


	public function SetPossibleValues(mixed ...$possibleValues): static
	{
		$this->possibleValues = $possibleValues;
		return $this;
	}

	public function AddPossibleValue(mixed ...$possibleValue): static
	{
		array_push($this->possibleValues, ...$possibleValue);
		return $this;
	}


	public function IsAllowingMultiple(): bool
	{
		return $this->allowMultiple;
	}

	public function AllowsValue(mixed $value): bool
	{
		return in_array($value, $this->possibleValues);
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

	public function GetPossibleValues(): array
	{
		return $this->possibleValues;
	}

	public function verify(array $data): static
	{
		$value = $this->getValue($data);

		$this->verifyRequired($value);
		$this->verifyConditions($value);

		$this->isVerified = true;
		return $this;
	}

	public function verifyValueIsPossible(mixed $value): bool
	{
		if ($this->possibleValues === null)
			return true;

		if ($this->allowsValue($value))
			return true;
		
		$this->addError($this->regexErrorMessage ?? "Value does not match the requirements");
		return false;
	}

}