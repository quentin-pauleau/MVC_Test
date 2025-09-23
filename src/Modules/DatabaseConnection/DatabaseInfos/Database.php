<?php
namespace Modules\DatabaseConnection\DatabaseInfos;

use Exception;
use Modules\DatabaseConnection\DatabaseConnection;
use Modules\DatabaseConnection\DatabaseInfos\DatabaseTable;
use Modules\DatabaseConnection\QueryBuilder\CreateQueryBuilder\CreateTableQueryBuilder;
use Modules\DatabaseConnection\QueryBuilder\QueryBuilder;

class Database
{

	private string $name;


	/**
	 * @var DatabaseTable[]
	 */
	private array $tables;


	private $triggers;


	public function __construct(string $name)
	{
		$this->name = $name;
	}


	public function GetTables(): array
	{
		return $this->tables;
	}



	public function GetTriggers(): array
	{
		return $this->triggers;
	}


	#region Queries
	public function ShowTable(): array
	{
		return $this->tables;
	}

	public function ShowTriggers(): array
	{
		throw new Exception("NotImplementedYet");
	}


	public function CreateTable(string $name): CreateTableQueryBuilder
	{
		return DatabaseConnection::GetConnection()->Query()->Create()->Table($name);
	}
}