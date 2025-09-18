<?php
namespace Modules\DatabaseConnection\DatabaseQueryBuilder;

use Modules\DatabaseConnection\QueryBuilder\AbstractQueryBuilder;
use PDO;

class ShowQueryBuilder extends AbstractQueryBuilder
{
	public function __construct() {}


	/**
	 * 
	 * @return 
	 */
	public function Table(string $filter = ''): array {
		$query = "SHOW TABLES LIKE :filter ;";

		$stmt = $this->pdo->prepare($query);

		$stmt->bindValue(":filter", $filter, PDO::PARAM_STR);

		$stmt->execute();

		$stmt->fetchAll(PDO::FETCH_ASSOC);

		$tables = [];

		return $tables;
	}

	public function Field(string $tableName, string $filter = ''): array {
		$query = "SHOW COLUMNS FROM {$tableName} LIKE :filter;";

		$stmt = $this->pdo->prepare($query);

		$stmt->bindValue(":filter", $filter, PDO::PARAM_STR);

		$stmt->execute();

		return [];
	}
}