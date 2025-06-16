<?php
namespace Utils\Database\QueryBuilder;



abstract class DatabaseQuery
{
	protected string $query = '';

	public array $conditions = [];


	abstract public function build(): string;


	public function Equals(
		string $column,
		$value,
		bool $not
	): DatabaseQuery {
		$this->conditions[] = "$column = $value ";
		return $this;
	}


	public function Like(
		string $column,
		$value,
		bool $not
	): DatabaseQuery {
		$this->conditions[] = "$column LIKE '%$value%'";
		return $this;
	}
}