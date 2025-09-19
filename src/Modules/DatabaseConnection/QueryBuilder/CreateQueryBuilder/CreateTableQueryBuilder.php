<?php
namespace Modules\DatabaseConnection\QueryBuilder\CreateQueryBuilder;

use Exception;
use Modules\Collection\GenericList;
use Modules\DatabaseConnection\DatabaseInfos\DatabaseField;
use Modules\DatabaseConnection\DatabaseInfos\DatabaseTable;
use Modules\DatabaseConnection\DatabaseQuery;
use Modules\DatabaseConnection\QueryBuilder\AbstractQueryBuilder;
use PDO;

class CreateTableQueryBuilder extends AbstractQueryBuilder
{
	/**
	 * Create the table if a table already exists with that name, an error will be thrown.
	 * @var string
	 */
	public const CREATION_TYPE_CREATE = 'CREATE';

	/**
	 * Create the table or replace it if a table already exists with that name.
	 * @var string
	 */
	public const CREATION_TYPE_REPLACE = 'REPLACE';

	/**
	 * Create the table if no table exists with this name.
	 * 
	 * Default value.
	 * @var string
	 */
	public const CREATION_TYPE_IF_NOT_EXISTS = 'IF NOT EXISTS';


	private string $tableName;
	private array $fields;
	private string $comment = '';

	private string $creationType = self::CREATION_TYPE_IF_NOT_EXISTS;


	public function __construct(PDO $pdo, string $tableName)
	{
		parent::__construct($pdo);

		if ($tableName === '' || !preg_match('/^[a-zA-Z0][a-zA-Z0-9_]*$/i', $tableName))
			throw new Exception("Invalid table name : '{$tableName}')");

		$this->tableName = $tableName;
	}

	public static function FromTable(PDO $pdo, DatabaseTable $table): self {
		$builder = new self($pdo, $table->getName());

		foreach($table->GetFields() as $field)
			$builder->AddField($field);

		return $builder;
	}


	public function SetComment(string $comment): self {
		$this->comment = $comment;
		return $this;
	}

	public function RenameTable(string $tableName): self {
		$this->tableName = $tableName;
		return $this;
	}


	public function SetCreationType(string $creationType): self {
		return $this;
	}


	public function RenameField(string $oldFieldName, string $newFieldName): self {
		if (!array_key_exists($oldFieldName, $this->fields))
			throw new Exception("Unknown old field name : {$oldFieldName}");

		if (isset($this->fields[$newFieldName]))
			throw new Exception("Duplicate new field name : {$newFieldName}");

		$this->fields[$newFieldName] = $this->fields[$oldFieldName];

		unset($this->fields[$oldFieldName]);
		return $this;
	}


	public function AddField(DatabaseField ...$field): self {
		foreach($field as $f) {
			if (isset($this->fields[$f->getName()]))
				throw new Exception("Duplicate field name : {$f->GetName()}");

			$this->fields[$f->getName()] = $f;
		}

		return $this;
	}


	public function RemoveField(string ...$fieldName): self {
		foreach($fieldName as $fn) {
			if (!array_key_exists($fn, $this->fields))
				throw new Exception("Unknown field name : {$fn}");

			unset($this->fields[$fn]);
		}

		return $this;
	}


	public function AddRelation(string $localKeyName, string $foreignTableName, string $foreignKeyName): self {
		if (!array_key_exists($localKeyName, $this->fields))
			throw new Exception("Unknown local key name : {$localKeyName}");

		return $this;
	}


	public function Build(): DatabaseQuery {
		$fields = implode(',', array_map(fn($f) => $f->CreationString(), $this->fields));

		$query = match ($this->creationType) {
			self::CREATION_TYPE_IF_NOT_EXISTS => <<<SQL
				CREATE TABLE IF NOT EXISTS `{$this->tableName}`
				(
					{$fields}
				)
				COMMENT='{$this->comment}',
				SQL,

			self::CREATION_TYPE_CREATE => <<<SQL
				CREATE TABLE `{$this->tableName}`
				(
					{$fields}
				)
				COMMENT='{$this->comment}',
				SQL,
			
			self::CREATION_TYPE_REPLACE => <<<SQL
				CREATE OR REPLACE TABLE `{$this->tableName}`
				(
					{$fields}
				)
				COMMENT='{$this->comment}',
				SQL,

			default => throw new Exception('Invalid creation type'),
		};
		
		

		return new DatabaseQuery($this->pdo, $query);
	}
}