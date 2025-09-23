<?php
namespace Modules\DatabaseConnection\QueryBuilder;

use PDO;

class InsertionQueryBuilder extends AbstractQueryBuilder
{

	private string $table;

	public function __toString(): string
	{
		$query = "INSERT INTO {$this->table}";

		

		return $query;
	}

	public function __construct(PDO $pdo, string $table)
	{
		parent::__construct($pdo);

		$this->table = $table;
	}


	public function build(): void
	{
		
	}
}