<?php
namespace Modules\FormHandling;

use DateTime;
use DateTimeImmutable;

class InputNumberHandler extends InputFieldHandler
{
	private ?DateTimeImmutable $minDate;
	private ?DateTimeImmutable $maxDate;

	private ?string $minDateErrorMessage;
	private ?string $maxDateErrorMessage;


	public function SetMinDate(DateTimeImmutable $min, ?string $errorMessage = null): static
	{
		$this->minDate = $min;
		$this->minDateErrorMessage = $errorMessage;
		return $this;
	}

	public function SetMaxDate(DateTimeImmutable $max, ?string $errorMessage = null): static
	{
		$this->maxDate = $max;
		$this->maxDateErrorMessage = $errorMessage;
		return $this;
	}
	


	public function HasMinDate(): bool
	{
		return $this->minDate !== null;
	}

	public function HasMaxDate(): bool
	{
		return $this->maxDate !== null;
	}

	public function getValue(array $data): DateTimeImmutable|null
	{
		return isset($data[$this->getName()])
			? DateTimeImmutable::createFromFormat('Y-m-d', $data[$this->getName()])
			: null;
	}

	public function GetMinDate(): DateTimeImmutable|null
	{
		return $this->minDate;
	}

	public function GetMaxDate(): DateTimeImmutable|null
	{
		return $this->maxDate;
	}


	public function verify(array $data): static
	{
		$value = $this->getValue($data);

		$this->verifyRequired($value);
		$this->verifyMinDate($value);
		$this->verifyMaxDate($value);
		$this->verifyConditions($value);

		$this->isVerified = true;
		return $this;
	}

	public function verifyMinDate(mixed $value): bool
	{
		if (!$this->HasMinDate())
			return true;
		
		if ($value >= $this->minDate)
			return true;
		
		$this->addError($this->minDateErrorMessage ?? "Value must be before the minimum date {$this->minDate->format("d/m/Y")}");
		return false;
	}
	
	public function verifyMaxDate(mixed $value): bool
	{
		if (!$this->HasMaxDate())
			return true;
		
		if ($value <= $this->maxDate)
			return true;
		
		$this->addError($this->maxDateErrorMessage ?? "Value must be after the maximum date {$this->maxDate->format("d/m/Y")}");
		return false;
	}
}