<?php
namespace Modules\ORM\RecordManager;

use Exception;
use PDO;
use ReflectionClass;
use Core\Database\Database;
use Core\Database\DatabaseQueryParam;
use Core\Database\ListDatabaseQueryParam;
use Feature\EntityToDatabase\Attributes\BindField;

final class RecordManager
{
	protected static Database $connection;

	protected string $recordClass;
	protected string $listrecordClass;
	protected string $tableName;

	/**
	 * @var RecordPropertyManager[]
	 */
	protected array $fields;
	protected string $idField;

	protected function __construct(string $recordClass)
	{
		//* set the targeted record class
		if (array_search(DatabaseRecord::class, class_parents($recordClass)))
			throw new Exception('The record must be a subclass of record');	

		self::$recordClass = $recordClass;

		//* set the table binding
		$reflection = new ReflectionClass($this->recordClass);
		self::$tableName = $reflection->getAttributes(DatabaseRecord::class)[0]->getArguments()[0];
		self::$tableName = strtolower(self::$tableName);

		//* set the id field
		$this->idField = strtoupper($this->tableName).'_ID';

		//* set the fields bindings
		$this->fields = [];
		foreach ($reflection->getProperties() as $property) {
			$field = $property->getAttributes(BindField::class);

			if (count($field) == 0)
				continue; //* property isnt bind to a field in the database

			$this->fields[] = new RecordPropertyManager(
				$this->recordClass,
				$property->getName(),
			);
		}
	}

	/**
	 * @return recordPropertyManager[]
	 */
	public function GetFields(): array {
		return $this->fields;
	}

	public function GetFieldSelection(bool $withId = true): string {
		$selection = '';

		if ($withId)
			$selection .= "`$this->idField`, ";

		foreach ($this->fields as $field)
			$selection .= ', `'.$field->GetFieldName().'`';

		$selection = substr($selection, 0, -1);
		return $selection;
	}

	public function GetFieldUpdateSelection(bool $withId = false): string {
		$selection = '';

		if ($withId)
			$selection .= "$this->idField = :id, ";

		foreach ($this->fields as $field)
			$selection .= '`'.$field->GetFieldName().'` = :'.$field->GetFieldName().', ';

		$selection = substr($selection, 0, -1);
		return $selection;
	}

	public function GetFieldUpdateParameters(DatabaseRecord $record): ListDatabaseQueryParam {
		$params = new ListDatabaseQueryParam();

		foreach ($this->fields as $field)
			$params->Add(
				new DatabaseQueryParam(
					':'.$field->GetFieldName(),
					$field->ExportValue($record)
				)
			);

		return $params;
	}

	public function NewRecord(array $item): DatabaseRecord {
		$record = new $this->recordClass;

		foreach ($this->fields as $field)
			if (isset($item[$field->GetFieldName()]))
				$field->ImportValue($record, $item[$field->GetFieldName()]);

		return $record;
	}

	public function NewList(array $items): array {
		$list = [];

		foreach ($items as $item)
			$list[] = self::Newrecord($item);

		return $list;
	}


	public function CreateQuery(): recordManagerQuery {
		return new recordManagerQuery($this);
	}


	#region Reading operations

	/**
	 * Find the record matching the id in the context
	 * @param int $id
	 * @throws Exception If there is no record with the given id in the context
	 * @return DatabaseRecord
	 */
	public function ForceFind(int $id): DatabaseRecord {
		$query = <<<SQL
		SELECT {$this->GetFieldSelection()}
		FROM `{$this->tableName}`
		WHERE `{$this->idField}` = :id
		SQL;

		$item = $this->connection->GetOne(
			$query,
			new ListDatabaseQueryParam(
				new DatabaseQueryParam(':id', $id, PDO::PARAM_INT)
			)
		);

		if ($item === [])
			throw new Exception("No record found for the id '$id'");

		return self::NewRecord($item);
	}


	/**
	 * Find the record matching the id in the context
	 * @param int $id
	 * @return DatabaseRecord|null
	 */
	public function Find(int $id): DatabaseRecord|null {
		$query = <<<SQL
		SELECT {$this->GetFieldSelection()}
		FROM `{$this->tableName}`
		WHERE `{$this->idField}` = :id
		SQL;

		$item = $this->connection->GetOne(
			$query,
			new ListDatabaseQueryParam(
				new DatabaseQueryParam(':id', $id, PDO::PARAM_INT)
			)
		);

		return $item != [] ? self::NewRecord($item) : null;
	}


	/**
	 * Get all entities from the database
	 * @return DatabaseRecord[]
	 */
	public function FindAll(): array {
		$query = <<<SQL
		SELECT {$this->GetFieldSelection()}
		FROM `{$this->tableName}`
		SQL;

		$items = $this->connection->GetList(
			$query,
		);

		return $this->NewList($items);
	}


	/**
	 * Verify if an enity exists with the given ID
	 * @param int $id
	 * @return bool
	 */
	public function Any(int $id): bool {
		$query = <<<SQL
		SELECT `{$this->idField}`
		FROM `{$this->tableName}`
		WHERE `{$this->idField}` = :id
		SQL;

		$item = $this->connection->GetOne(
			$query,
			new ListDatabaseQueryParam(
				new DatabaseQueryParam(':id', $id, PDO::PARAM_INT)
			)
		);

		return $item != [];
	}


	/**
	 * Find all entities that matches the given parameters
	 * 
	 * Based the export type : 
	 * - int, float, bool and objects => uses the '=' operator
	 * - string => uses the 'LIKE' operator
	 * - array => uses the 'IN' operator
	 * - null => uses the 'IS NULL' operator
	 * 
	 * @param array $params
	 * @return DatabaseRecord[] All records matching the given parameters
	 */
	public function FindMatch(array $params): array {
		$query = <<<SQL
		SELECT {$this->GetFieldSelection()}
		FROM `{$this->tableName}` 
		SQL;

		foreach ($this->fields as $field) {
			if (isset($params[$field->GetFieldName()]))
				continue;

			$param = $params[$field->GetFieldName()];

			switch(gettype($param)) {
				case 'int':
				case 'float':
				case 'bool':
				case 'object':
					$query .= "`{$field->GetFieldName()}` = :{$field->GetConverterClass()::ExportValue($param)} AND ";
					break;
				
				case 'string':
					$query .= "`{$field->GetFieldName()}` LIKE :{$field->GetConverterClass()::ExportValue($param)} AND ";
					break;
				
				case 'array':
					$values = array_map(fn($v) => $field->GetConverterClass()::ExportValue($v), $param);
					$query .= "`{$field->GetFieldName()}` IN ('".implode(',', $values)."') AND ";;
					break;

				case 'null':
					$query .= "`{$field->GetFieldName()}` IS NULL AND ";
					break;

				default:
					$type = gettype($param);
					throw new Exception("Unsupported parameter type '{$type}'");
			}
		}

		if (str_ends_with($query, 'AND '))
			$query = substr($query, 0, -4);
		
		$items = $this->connection->GetList(
			$query,
		);

		return $this->NewList($items);
	}

	#endregion Reading operations



	#region Update and Inserting operations

	/**
	 * Update a given record into the database
	 * @param DatabaseRecord $record
	 * @return void
	 */
	public function ForceUpdate(DatabaseRecord $record): bool {
		if (!($record instanceof $this->recordClass))
			throw new Exception("The record must be an instance of '{$this->recordClass}'");

		if (!$this->Any($record->{$this->idField}))
			throw new Exception("record to update does not exist in the context '{$this->recordClass}' with the id '{$this->idField}'");
		
		$query = <<<SQL
		UPDATE {$this->tableName} SET
		{$this->GetFieldUpdateSelection()}
		WHERE `{$this->idField}` = :id
		SQL;

		$result = $this->connection->Update(
			$query,
			$this->GetFieldUpdateParameters($record)
		);

		if ($result < 1)
			throw new Exception("Failed to update the record '{$this->recordClass}' with the id '{$this->idField}', there was not field to update");
		elseif ($result > 1)
			throw new Exception("An unepected behavior occured while updating the record in the context '{$this->recordClass}' with the id '{$this->idField}', {$result} record have been updated");

		return true;
	}


	/**
	 * Try to update a given record in the context
	 * @param DatabaseRecord $record
	 * @return void
	 */
	public function TryUpdate(DatabaseRecord $record): bool {
		if (!($record instanceof $this->recordClass))
			throw new Exception("The record must be an instance of '{$this->recordClass}'");
		
		$query = <<<SQL
		UPDATE {$this->tableName} SET
		{$this->GetFieldUpdateSelection()}
		WHERE `{$this->idField}` = :id
		SQL;

		$result = $this->connection->Update(
			$query,
			$this->GetFieldUpdateParameters($record)
		);

		if ($result > 1)
			throw new Exception("An unepected behavior occured while updating the record in the context '{$this->recordClass}' with the id '{$this->idField}', {$result} record have been updated");

		return true;
	}


	/**
	 * Insert a new record in the context
	 * @param DatabaseRecord $record
	 * @return int The id of the new record
	 */
	public function New(DatabaseRecord $record): int {
		if (!($record instanceof $this->recordClass))
			throw new Exception("The record must be an instance of '{$this->recordClass}'");

		$query = <<<SQL
		INSERT INTO {$this->tableName} 
		({$this->GetFieldSelection(false)})
		VALUES
		({$this->GetFieldUpdateSelection(true)});
		SQL;

		$id = $this->connection->InsertOne(
			$query,
			$this->GetFieldUpdateParameters($record)
		);

		return $id;
	}


	/**
	 * Save the changed made to record in the context, if the record it will be created
	 * @param DatabaseRecord $record
	 * @throws \Exception
	 * @return int
	 */
	public function Save(DatabaseRecord $record): int {
		if (!($record instanceof $this->recordClass))
			throw new Exception("The record must be an instance of '{$this->recordClass}'");

		if ($record->id != null && $this->Any($record->id)) {
			$this->TryUpdate($record);
			return $record->id;
		}
		
		
		$id = $this->New($record);
		$record->id = $id;
		return $id;
	}


	#endregion Update and Inserting operations


	#region Deleting operations

	public function Delete(DatabaseRecord $record): bool {
		if (!($record instanceof $this->recordClass))
			throw new Exception("The record must be an instance of '{$this->recordClass}'");

		$this->DeleteById($record->id);

		return true;
	}


	public function DeleteById(int $id): bool {
		$query = <<<SQL
		DELETE INTO {$this->tableName}
		WHERE `{$this->idField}` = :id
		SQL;

		$result = $this->connection->Delete(
			$query,
			new ListDatabaseQueryParam(
				new DatabaseQueryParam(':id', $id, PDO::PARAM_INT)
			)
		);

		if ($result < 1)
			throw new Exception("Failed to delete the record '{$this->recordClass}' with the id '{$this->idField}', nothing has been deleted");
		elseif ($result > 1)
			throw new Exception("An unepected behavior occured while deleting the record in the context '{$this->recordClass}' with the id '{$this->idField}', {$result} record have been deleted");

		return true;
	}

	#endregion Deleting operations



}