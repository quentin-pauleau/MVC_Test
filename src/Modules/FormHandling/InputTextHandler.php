<?php
namespace Modules\FormHandling;



class InputTextHandler extends InputFieldHandler
{
	private ?string $regex;
	private ?int $minLength;
	private ?int $maxLength;


	private ?string $regexErrorMessage;
	private ?int $minLengthErrorMessage;
	private ?int $maxLengthErrorMessage;


	public function SetRegex(string $regex, ?string $errorMessage = null): static
	{
		$this->regex = $regex;
		$this->regexErrorMessage = $errorMessage;
		return $this;
	}

	public function SetMinLength(int $length, ?string $errorMessage = null): static
	{
		$this->minLength = $length;
		$this->minLengthErrorMessage = $errorMessage;
		return $this;
	}

	public function SetMaxLength(int $length, ?string $errorMessage = null): static
	{
		$this->maxLength = $length;
		$this->maxLengthErrorMessage = $errorMessage;
		return $this;
	}


	public function HasRegex(): bool
	{
		return $this->regex !== null;
	}

	public function HasMinLength(): bool
	{
		return $this->minLength !== null;
	}

	public function HasMaxLength(): bool
	{
		return $this->maxLength !== null;
	}


	/**
	 * 
	 * @param array $data
	 * @return string the value
	 * @return false the value isnt a valid text
	 * @return null the data doesnt contain a value for this field name
	 */
	public function getValue(array $data): string|false|null
	{
		if (empty($data[$this->getName()]))
			return null;

		return filter_string($data[$this->getName()]);
	}

	public function GetRegex(): string|null
	{
		return $this->regex;
	}

	public function GetMinLength(): int|null
	{
		return $this->minLength;
	}

	public function GetMaxLength(): int|null
	{
		return $this->maxLength;
	}


	public function verify(array $data): static
	{
		$value = $this->getValue($data);

		$this->verifyRequired($value);
		$this->verifyMinLength($value);
		$this->verifyMaxLength($value);
		$this->verifyConditions($value);

		$this->isVerified = true;
		return $this;
	}

	public function verifyRegex(mixed $value): bool
	{
		if (!$this->HasRegex())
			return true;

		if (!preg_match($this->regex, $value))
			return true;
		
		$this->addError($this->regexErrorMessage ?? "Value does not match the requirements");
		return false;
	}

	public function verifyMinLength(mixed $value): bool
	{
		if (!$this->HasMinLength())
			return true;
		
		if (strlen($value) >= $this->minLength)
			return true;
		
		$this->addError($this->minLengthErrorMessage ?? "Value must be longer than the minimum value {$this->minLength}");
		return false;
	}
	
	public function verifyMaxLength(mixed $value): bool
	{
		if (!$this->HasMaxLength())
			return true;
		
		if (strlen($value) <= $this->maxLength)
			return true;
		
		$this->addError($this->maxLengthErrorMessage ?? "Value must be shorter than the maximum value {$this->maxLength}");
		return false;
	}
}