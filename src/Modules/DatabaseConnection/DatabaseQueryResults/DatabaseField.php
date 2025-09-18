<?php
namespace Modules\DatabaseConnection\DatabaseInfos;

use Modules\DatabaseConnection\DatabaseConnection;
use PDO;

/**
 * Represent a field in a database table.
 * 
 * Instanciated by {@see DatabaseConnection::ShowTable()}
 */
class DatabaseField
{
	private string $tableName;
	private ?DatabaseTable $tale = null;

	private string $name;
	private string $type;
	private bool $isNullable;
	private int $size;
	private bool $isPrimaryKey;
	private bool $isUniqueKey;
	private mixed $defaultValue;
	private bool $isAutoIncrement;

	public function __construct(
		string $name, 
		string $type, 
		bool $isNullable, 
		int $size,
		bool $isPrimaryKey, 
		bool $isUniqueKey, 
		mixed $defaultValue,
		bool $isAutoIncrement,
	)
	{
		$this->name = $name;
		$this->type = $type;
		$this->size = $size;
		$this->isNullable = $isNullable;
		$this->isPrimaryKey = $isPrimaryKey;
		$this->isUniqueKey = $isUniqueKey;
		$this->defaultValue = $defaultValue;
		$this->isAutoIncrement = $isAutoIncrement;
	}


	public function getName(): string
	{
		return $this->name;
	}

	public function getType(): string
	{
		return $this->type;
	}

	public function isNullable(): bool
	{
		return $this->isNullable;
	}

	public function getSize(): int
	{
		return $this->size;
	}

	public function IsPrimaryKey(): bool
	{
		return $this->isPrimaryKey;
	}

	public function IsUniqueKey(): bool
	{
		return $this->isUniqueKey;
	}

	public function getDefaultValue()
	{
		return $this->defaultValue;
	}

	public function isAutoIncrement(): bool
	{
		return $this->isAutoIncrement;
	}



	public function GetTableName(): string {
		return $this->tableName;
	}


	public function GetTable(): DatabaseTable {
		DatabaseConnection::Instance()->

		return $this->table;
	}
}