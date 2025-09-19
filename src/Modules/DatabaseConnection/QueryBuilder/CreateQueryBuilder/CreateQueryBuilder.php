<?php
namespace Modules\DatabaseConnection\QueryBuilder\CreateQueryBuilder;

use Modules\DatabaseConnection\DatabaseInfos\DatabaseField;
use Modules\DatabaseConnection\DatabaseInfos\DatabaseTable;
use Modules\DatabaseConnection\DatabaseInfos\Database;
use Modules\DatabaseConnection\QueryBuilder\AbstractQueryBuilder;

class CreateQueryBuilder extends AbstractQueryBuilder
{

	/**
	 * @param string|\Modules\DatabaseConnection\DatabaseInfos\DatabaseTable $table
	 * @return CreateTableQueryBuilder
	 */
	public function Table(string|DatabaseTable $table): CreateTableQueryBuilder {
		if ($table instanceof DatabaseTable)
			return CreateTableQueryBuilder::FromTable($this->pdo, $table);

		return new CreateTableQueryBuilder($this->pdo, $table);
	}


	public function Database(string|Database $database) : CreateDatabaseQueryBuilder {
		return new CreateDatabaseQueryBuilder($this->pdo, $database);
	}
}