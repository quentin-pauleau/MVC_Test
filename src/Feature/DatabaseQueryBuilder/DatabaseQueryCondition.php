<?php
namespace Feature\DatabaseQuery;


use DateTimeInterface;
use Core\Database\DatabaseQueryParam;
use Core\Database\QueryBuilder\DatabaseSelectionQuery;

abstract class DatabaseQueryCondition
{
	/**
	 * 
	 * @var string[]
	 */
	protected array $conditions = [];

	/**
	 * 
	 * @var DatabaseQueryParam[]
	 */
	protected array $params = [];

	protected int $count = 0;


	public function Equals(string $field, mixed $value): static {
		return $this->addCondition($field, "=", $value);
	}

	public function NotEquals(string $field, mixed $value): static {
		return $this->addCondition($field, "<>", $value);
	}


	public function Like(string $field, string $value): static {
		return $this->addCondition($field, "LIKE", $value);
	}
	

	public function NotLike(string $field, string $value): static {
		return $this->addCondition($field, "NOT LIKE", $value);
	}


	public function GreaterThan(string $field, int|float|DateTimeInterface $value): static {
		return $this->addCondition($field, ">", $value);
	}

	public function GreaterThanOrEquals(string $field, int|float|DateTimeInterface $value): static {
		return $this->addCondition($field, ">=", $value);
	}

	
	public function LessThan(string $field, int|float|DateTimeInterface $value): static {
		return $this->addCondition($field, "<", $value);
	}

	public function LessThanOrEquals(string $field, int|float|DateTimeInterface $value): static {
		return $this->addCondition($field, "<=", $value);
	}

	public function In(string $field, array|DatabaseSelectionQuery $options): static {
		if (is_array($options))
			return $this->InArray($field, $options);

		return $this->InSelection($field, $options);
	}

	public function InArray(string $field, array $options): static {
		$condition = "{$field} IN (";

		foreach ($options as $option) {
			$value = $option; // copy the original value to keep the original data intact

			// convert the value if necessary
			//TODO: implement a general conversion system in an other function and remove this one
			if (is_string($value))
				$value = "\"{$value}\"";
			elseif (is_bool($value))
				$value = $value ? 1 : 0;
			elseif ($option instanceof DateTimeInterface)
				$value = $value->format('Y-m-d H:i:s');

			$condition .= ":p{$this->count}, ";
			trim($value, ",");
			$this->params[$this->count] = new DatabaseQueryParam(":p{$this->count}", $value);
		}

		$condition .= ")";

		$this->conditions[$this->count] = $condition;
		$this->count++;
		return $this;
	}

	public function InSelection(string $field, DatabaseSelectionQuery $selection): static {

		$this->conditions[$this->count] = "{$field} IN ({$selection->Build()})";
		return $this;
	}

	public function OrSimple(): static {
		$this->conditions[$this->count - 1] .= " OR ";
		return $this;
	}
	
	/**
	 * Get the current conditions array
	 * 
	 * @return array
	 */
	public function getConditions(): array {
		return $this->conditions;
	}
	
	/**
	 * Get the current parameters array
	 * 
	 * @return array
	 */
	public function getParams(): array {
		return $this->params;
	}
	
	/**
	 * Adds a condition to the query
	 * 
	 * @param string $field The field to check
	 * @param string $operator The operator to use
	 * @param mixed $value The value to compare against
	 * @return static
	 */
	protected function addCondition(string $field, string $operator, mixed $value): static {
		$paramName = ":p{$this->count}";
		$this->conditions[] = "{$field} {$operator} {$paramName}";
		$this->params[$this->count] = new DatabaseQueryParam($paramName, $value);
		$this->count++;
		return $this;
	}
}