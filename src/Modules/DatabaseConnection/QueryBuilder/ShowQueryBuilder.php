<?php
namespace Modules\DatabaseConnection\QueryBuilder;

use Modules\DatabaseConnection\DatabaseInfos\DatabaseField;
use Modules\DatabaseConnection\DatabaseInfos\DatabaseTable;
use Modules\DatabaseConnection\QueryBuilder\AbstractQueryBuilder;
use PDO;

class ShowQueryBuilder extends AbstractQueryBuilder
{

	/**
	 * 
	 * @return 
	 */
	public function AllTables(string $filter = ''): array {
		if ($filter === '')
			$filter = '%';

		$stmt = $this->pdo->prepare('SHOW FULL TABLES LIKE :filter ;');
		$stmt->bindValue(":filter", $filter, PDO::PARAM_STR);

		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$tables = [];

		foreach ($result as $row)
			$tables[] = new DatabaseTable(
				$row['Tables_in_test'],
				$row['Table_type'],
			);


		return $tables;
	}

	public function Table(string $name): DatabaseTable {
		$stmt = $this->pdo->prepare('SHOW FULL TABLES LIKE :filter ;');
		$stmt->bindValue(":filter", $name, PDO::PARAM_STR);

		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		
		$table = new DatabaseTable(
			$result['Tables_in_test'],
			$result['Table_type'],
		);


		return $table;
	}


	public function TableField(string|DatabaseTable $table, string $name): DatabaseField {
		if ($table instanceof DatabaseTable)
			$table = $table->getName();

		$stmt = $this->pdo->prepare('SHOW COLUMNS FROM `:tableName` LIKE :filter ;');
		$stmt->bindValue(":tableName", $table, PDO::PARAM_STR);
		$stmt->bindValue(":fieldName", $name, PDO::PARAM_STR);

		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		$field = new DatabaseField(
			$result['Field'] ?? '',
			$result['Type'] ?? 'unknown',
			($result['Null'] ?? null) === 'YES',
			0,
			($result['Key'] ?? null) === 'PRI',
			($result['Key'] ?? null) === 'UNI',
			($result['Default'] ?? null),
			($result['Extra'] ?? null) === 'auto_increment',
		);

		return $field;
	}

	public function AllTableFields(string|DatabaseTable $table, string $filter = ''): array {
		if ($table instanceof DatabaseTable)
			$table = $table->getName();

		$stmt = $this->pdo->prepare('SHOW COLUMNS FROM `:tableName` LIKE :filter ;');
		$stmt->bindValue(":tableName", $table, PDO::PARAM_STR);
		$stmt->bindValue(":filter", $filter, PDO::PARAM_STR);

		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$fields = [];

		foreach ($result as $row)
			$fields[] = new DatabaseField(
				$row['Name'] ?? '',
				$row['Type'] ?? 'unknown',
				($row['Null'] ?? '') === 'YES',
				($row['Null'] ?? '') === 'YES',
				($row['Null'] ?? '') === 'YES',
				($row['Null'] ?? '') === 'YES',
				($row['Null'] ?? '') === 'YES',
				($row['Auto_increment'] ?? '') === 'YES',
			);

		return $fields;
	}
}