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
	private ?DatabaseTable $table = null;

	private string $name;
	private string $type;
	private ?bool $isNullable;
	private ?int $size;
	private mixed $defaultValue;

	private ?bool $isPrimaryKey;
	private ?bool $isUniqueKey;
	
	private ?bool $isAutoIncrement;

	public function __toString(): string
	{
		return "{$this->name}({$this->type})";
	}

	public function __construct(
		string $name, 
		string $type, 
		?bool $isNullable = null, 
		?int $size = null,
		?bool $isPrimaryKey = null, 
		?bool $isUniqueKey = null, 
		mixed $defaultValue = null,
		?bool $isAutoIncrement = null,

		?DatabaseTable $table = null,
	)
	{
		$this->name = $name;

		$this->type = $type;
		$this->size = $size;
		$this->isNullable = $isNullable;
		$this->defaultValue = $defaultValue;

		$this->isPrimaryKey = $isPrimaryKey;
		$this->isUniqueKey = $isUniqueKey;
		$this->isAutoIncrement = $isAutoIncrement;

		$this->table = $table;
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
		if ($this->table === null)
			$this->InitTable();

		return $this->table;
	}

	public function InitTable(): void {
		$this->table = DatabaseConnection::GetConnection()->Query()->Show()->Table($this->tableName);
	}


	public function CreationString(): string {
		$str = "`{$this->getName()}` {$this->getType()}";
			
		if ($this->getSize())
			$str .= "({$this->getSize()})";

		if ($this->isNullable())
			$str .= ' NOT NULL';

		if ($this->getDefaultValue())
			$str .= " DEFAULT '{$this->getDefaultValue()}'";

		if ($this->IsPrimaryKey())
			$str .= ' PRIMARY KEY';

		if ($this->IsUniqueKey())
			$str .= ' UNIQUE';

		if ($this->isAutoIncrement())
			$str .= ' AUTO_INCREMENT';

		return $str;
	}
}