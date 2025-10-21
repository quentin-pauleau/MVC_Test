<?php
namespace Modules\FormHandling;



class InputNumberHandler extends InputFieldHandler
{
	// private const DEFAULT_MIN_VALUE_ERROR_MESSAGE ="Value must be bigger than the minimum value: {$this->minValue}";
	// private const DEFAULT_MAX_VALUE_ERROR_MESSAGE = "Value must be smaller than the maximum value: {$this->maxValue}";
	// private const DEFAULT_PRECISION_ERROR_MESSAGE = "Value must have less than {$this->precision} digits after decimal point.";


	private ?float $minValue;
	private ?float $maxValue;
	
	/**
	 * It specify how many digits after the decimal point are allowed.
	 * If not set, no restriction is applied.
	 * @var 
	 */
	private ?int $precision;


	private ?string $minValueErrorMessage;
	private ?string $maxValueErrorMessage;
	private ?string $precisionErrorMessage;


	public function SetMinValue(float|null $min, ?string $errorMessage = null): static
	{
		$this->minValue = $min;
		$this->minValueErrorMessage = $errorMessage;
		return $this;
	}

	public function SetMaxValue(float|null $max, ?string $errorMessage = null): static
	{
		$this->maxValue = $max;
		$this->maxValueErrorMessage = $errorMessage;
		return $this;
	}

	/**
	 * Specify how many digits after the decimal point are allowed.
	 * If not set, no restriction is applied.
	 * @param int|null $precision
	 * @return static
	 */
	public function SetPrecision(int|null $precision): static
	{
		$this->precision = $precision;
		return $this;
	}

	public function HasMinValue(): bool
	{
		return $this->minValue !== null;
	}

	public function HasMaxValue(): bool
	{
		return $this->maxValue !== null;
	}

	public function HasPrecision(): bool
	{
		return $this->precision !== null;
	}




	/**
	 * 
	 * @param array $data
	 * @return float the value
	 * @return false the value isnt a valid number (int or float)
	 * @return null the data doesnt contain a value for this field name
	 */
	public function getValue(array $data): float|false|null
	{
		if (empty($data[$this->getName()]))
			return null;

		return filter_float($data[$this->getName()]);
	}

	public function GetMinValue(): float|null
	{
		return $this->minValue;
	}

	public function GetMaxValue(): float|null
	{
		return $this->maxValue;
	}

	public function GetPrecision(): int|null
	{
		return $this->precision;
	}


	public function verify(array $data): static
	{
		$value = $this->getValue($data);

		if ($value === false) {
			$this->addError("Invalid input");
			return $this;
		}

		$this->verifyRequired($value);
		$this->verifyMinValue($value);
		$this->verifyMaxValue($value);
		$this->verifyPrecision($value);
		$this->verifyConditions($value);

		$this->isVerified = true;
		return $this;
	}

	public function verifyMinValue(mixed $value): bool
	{
		if (!$this->HasMinValue())
			return true;
		
		if ($value >= $this->minValue)
			return true;
		
		$this->addError($this->minValueErrorMessage ?? "Value cannot be smaller than the minimum value: {$this->minValue}");
		return false;
	}
	
	public function verifyMaxValue(mixed $value): bool
	{
		if (!$this->HasMaxValue())
			return true;
		
		if ($value <= $this->maxValue)
			return true;
		
		$this->addError($this->maxValueErrorMessage ?? "Value cannot be bigger than the maximum value: {$this->maxValue}");
		return false;
	}

	public function verifyPrecision(mixed $value): bool
	{
		if (!$this->HasPrecision())
			return true;

		if (!str_contains((string)$value, "."))
			return true;

		if (strlen(substr(strrchr((string)$value, '.'),1)) < $this->precision)
			return true;

		$this->addError($this->precisionErrorMessage ?? "Value cannot have more than {$this->precision} digits after decimal point.");
		return false;
	}
}