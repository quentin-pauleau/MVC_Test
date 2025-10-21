<?php
namespace Modules\FormHandling;

use Modules\Result\Result;

abstract class FieldHandler
{
	protected bool $isVerified = false;

	//
	private string $name;
	private bool $isRequired = false;
	private string $isRequiredMessage = "Required field";


	/**
	 * All conditions 
	 * @var array<string, callable>
	 */
	private array $conditions = [];

	/**
	 * All errors messages
	 */
	private array $errors = [];


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

	public function isVerified():bool
	{
		return $this->isVerified;
	}


	/**
	 * Return true if this field is required or false when obtionnal
	 * @return bool
	 */
	final public function isRequired(): bool 
	{
		return $this->isRequired;
	}

	final public function setIsRequired(bool $isRequired): static
	{
		$this->isRequired = $isRequired;
		return $this;
	}
	
	/**
	 * Set the field as required
	 * @return FieldHandler
	 */
	final public function Require(): static 
	{
		$this->isRequired = true;
		return $this;
	}

	/**
	 * Set the field as obtionnal
	 * @return FieldHandler
	 */
	final public function NotRequire(): static
	{
		$this->isRequired = false;
		return $this;
	}

	public function setRequiredMessage(string $requiredMessage): static
	{
		$this->isRequiredMessage = $requiredMessage;
		return $this;
	}


	/**
	 * Add a personnalised condition for the field
	 * @param string $errorMessage The error message that will be set if the condition is not verified
	 * @param callable $condition Must return a boolean value and can take one parameter which will be the value of the field
	 * 
	 * @example $field->addCondition("Must be greater than 10", fn(int $value) => $value > 10);
	 * @return FieldHandler
	 */
	public function addCondition(string $errorMessage, callable $condition): static
	{
		$this->conditions[$errorMessage] = [$condition];
		return $this;
	}

	// public function removeCondition(string $errorMessage): static
	// {
	// 	unset($this->conditions[$errorMessage]);
	// 	return $this;
	// }

	// public function removeAllConditions(): static
	// {
	// 	$this->conditions = [];
	// 	return $this;
	// }
	

	public function getValue(array $data): mixed
	{
		return $data[$this->getName()] ?? null;
	}


	public function getErrors(): array
	{
		if (!$this->isVerified)
			throw new \Exception("Impossible to get errors before verification");
		
		return $this->errors;
	}

	public function isValid(): bool
	{
		if (!$this->isVerified)
			throw new \Exception("Impossible to check validity before verification");

		return $this->errors === [];
	}

	public function isInvalid(): bool
	{
		return !$this->isValid();
	}


	public function addError(string $errorMessage): static
	{
		$this->errors[] = $errorMessage;
		return $this;
	}


	/**
	 * Summary of handle
	 * @param array $data
	 * @return Result<mixed, null>|Result<null, string[]>
	 */
	public function handle(array $data): Result
	{
		$this->verify($data);

		if($this->isValid())
			return Result::Success($this->getValue($data));
		
		return Result::Fail($this->getErrors());
	}

	
	public function verify(array $data): static
	{
		$value = $this->getValue($data);

		$this->verifyRequired($value);
		$this->verifyConditions($value);

		$this->isVerified = true;
		return $this;
	}

	public function verifyRequired(mixed $value): bool
	{
		if(!$this->isRequired())
			return true;
		
		if ($value === null) {
			$this->addError($this->isRequiredMessage);
			return false;
		}

		return true;
	}

	public function verifyConditions(mixed $value): array
	{
		$errors = [];

		foreach ($this->conditions as $message => $condition)
			if(!$condition($value)) {
				$this->errors[] = $message;
				$errors[] = $message;
			}
		
		return $errors;
	}
}