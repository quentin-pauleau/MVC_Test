<?php
namespace Modules\DatabaseConnection\DatabaseInfos;

use Modules\DatabaseConnection\DatabaseConnection;
use Modules\DatabaseConnection\QueryBuilder\SelectionQueryBuilder;
use PDO;

/**
 * Represent a table in the database.
 * 
 * Instanciated by {@see DatabaseConnection::ShowTable()}
 */
class DatabaseTable
{
	public const TYPE_TABLE = "BASE TABLE";
	public const TYPE_VIEW = "VIEW";
	public const TYPE_SEQUENCE = "SEQUENCE";

	private string $name;
	private string $type = self::TYPE_TABLE;
	private array $fields = [];


	/**
	 * Summary of __construct
	 * @param string $name
	 * @param self::TYPE_TABLE | self::TYPE_VIEW | self::TYPE_SEQUENCE $type
	 */
	public function __construct(string $name, string $type)
	{
		$this->name = $name;
	}


	public function getName(): string
	{
		return $this->name;
	}


	/**
	 * Fields of this table.
	 * @return array
	 */
	public function GetFields(): array {
		return $this->fields;
	}



	public function ShowFields(): void
	{
		$this->fields = DatabaseConnection::GetConnection()->Query()->Show()->AllTableFields($this);
	}

	public function Select(): SelectionQueryBuilder {
		return DatabaseConnection::GetConnection()->Query()->Select()::FromTable($this);
	}
}