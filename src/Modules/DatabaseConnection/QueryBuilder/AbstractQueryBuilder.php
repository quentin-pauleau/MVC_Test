<?php
namespace Modules\DatabaseConnection\QueryBuilder;

use PDO;

abstract class AbstractQueryBuilder
{
	protected PDO $pdo;

	public function __construct(PDO $pdo)
	{
		$this->pdo = $pdo;
	}
}