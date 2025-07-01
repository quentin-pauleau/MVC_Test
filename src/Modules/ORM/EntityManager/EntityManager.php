<?php
namespace Feature\EntityToDatabase\EntutyManager;

use Exception;
use Feature\EntityToDatabase\Attributes\BindField;
use Feature\EntityToDatabase\Attributes\BindTable;
use Feature\EntityToDatabase\EntityManager\EntityManagerQuery;
use Models\Entities\Entity;
use Models\EntityLists\ListEntity;
use PDO;
use ReflectionClass;
use Feature\EntityToDatabase\EntityManager\EntityPropertyManager;
use Core\Database\Database;
use Core\Database\DatabaseQueryParam;
use Core\Database\ListDatabaseQueryParam;

abstract class EntityManager
{
	protected static Database $connection;

	protected string $entityClass;
	protected string $listEntityClass;
	protected string $tableName;

	/**
	 * @var EntityPropertyManager[]
	 */
	protected array $fields;
	protected string $idField;

	protected function __construct(string $entityClass)
	{
		//* set the targeted entity class
		if (array_search(Entity::class, class_parents($entityClass)))
			throw new Exception('The entity must be a subclass of Entity');	

		self::$entityClass = $entityClass;

		//* set the table binding
		$reflection = new ReflectionClass($this->entityClass);
		self::$tableName = $reflection->getAttributes(BindTable::class)[0]->getArguments()[0];
		self::$tableName = strtolower(self::$tableName);

		//* set the id field
		$this->idField = strtoupper($this->tableName).'_ID';

		//* set the fields bindings
		$this->fields = [];
		foreach ($reflection->getProperties() as $property) {
			$field = $property->getAttributes(BindField::class);

			if (count($field) == 0)
				continue; //* property isnt bind to a field in the database

			$this->fields[] = new EntityPropertyManager(
				$this->entityClass,
				$property->getName(),
			);
		}
	}

	/**
	 * @return EntityPropertyManager[]
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

	public function GetFieldUpdateParameters(Entity $Entity): ListDatabaseQueryParam {
		$params = new ListDatabaseQueryParam();

		foreach ($this->fields as $field)
			$params->Add(
				new DatabaseQueryParam(
					':'.$field->GetFieldName(),
					$field->ExportValue($Entity)
				)
			);

		return $params;
	}

	public function NewEntity(array $item): Entity {
		$Entity = new $this->entityClass;

		foreach ($this->fields as $field)
			if (isset($item[$field->GetFieldName()]))
				$field->ImportValue($Entity, $item[$field->GetFieldName()]);

		return $Entity;
	}

	public function NewList(array $items): ListEntity {
		$List = new $this->listEntityClass;

		foreach ($items as $item)
			$List->Add(self::NewEntity($item));

		return $List;
	}


	public function CreateQuery(): EntityManagerQuery {
		return new EntityManagerQuery($this);
	}


	#region Reading operations

	/**
	 * Find the entity matching the id in the context
	 * @param int $id
	 * @throws Exception If there is no entity with the given id in the context
	 * @return Entity
	 */
	public function ForceFind(int $id): Entity {
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
			throw new Exception("No entity found for the id '$id'");

		return self::NewEntity($item);
	}


	/**
	 * Find the entity matching the id in the context
	 * @param int $id
	 * @return Entity|null
	 */
	public function Find(int $id): Entity|null {
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

		return $item != [] ? self::NewEntity($item) : null;
	}


	/**
	 * Get all entities from the database
	 * @return ListEntity
	 */
	public function FindAll(): ListEntity {
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
	 * @return ListEntity Entities matching the given parameters
	 */
	public function FindMatch(array $params): ListEntity {
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
	 * Update a given entity into the database
	 * @param \Models\Entities\Entity $entity
	 * @return void
	 */
	public function ForceUpdate(Entity $entity): bool {
		if (!($entity instanceof $this->entityClass))
			throw new Exception("The entity must be an instance of '{$this->entityClass}'");

		if (!$this->Any($entity->{$this->idField}))
			throw new Exception("Entity to update does not exist in the context '{$this->entityClass}' with the id '{$this->idField}'");
		
		$query = <<<SQL
		UPDATE {$this->tableName} SET
		{$this->GetFieldUpdateSelection()}
		WHERE `{$this->idField}` = :id
		SQL;

		$result = $this->connection->Update(
			$query,
			$this->GetFieldUpdateParameters($entity)
		);

		if ($result < 1)
			throw new Exception("Failed to update the entity '{$this->entityClass}' with the id '{$this->idField}', there was not field to update");
		elseif ($result > 1)
			throw new Exception("An unepected behavior occured while updating the entity in the context '{$this->entityClass}' with the id '{$this->idField}', {$result} entity have been updated");

		return true;
	}


	/**
	 * Try to update a given entity in the context
	 * @param \Models\Entities\Entity $entity
	 * @return void
	 */
	public function TryUpdate(Entity $entity): bool {
		if (!($entity instanceof $this->entityClass))
			throw new Exception("The entity must be an instance of '{$this->entityClass}'");
		
		$query = <<<SQL
		UPDATE {$this->tableName} SET
		{$this->GetFieldUpdateSelection()}
		WHERE `{$this->idField}` = :id
		SQL;

		$result = $this->connection->Update(
			$query,
			$this->GetFieldUpdateParameters($entity)
		);

		if ($result > 1)
			throw new Exception("An unepected behavior occured while updating the entity in the context '{$this->entityClass}' with the id '{$this->idField}', {$result} entity have been updated");

		return true;
	}


	/**
	 * Insert a new entity in the context
	 * @param \Models\Entities\Entity $entity
	 * @return int The id of the new entity
	 */
	public function New(Entity $entity): int {
		if (!($entity instanceof $this->entityClass))
			throw new Exception("The entity must be an instance of '{$this->entityClass}'");

		$query = <<<SQL
		INSERT INTO {$this->tableName} 
		({$this->GetFieldSelection(false)})
		VALUES
		({$this->GetFieldUpdateSelection(true)});
		SQL;

		$id = $this->connection->InsertOne(
			$query,
			$this->GetFieldUpdateParameters($entity)
		);

		return $id;
	}


	/**
	 * Save the changed made to entity in the context, if the entity it will be created
	 * @param \Models\Entities\Entity $entity
	 * @throws \Exception
	 * @return int
	 */
	public function Save(Entity $entity): int {
		if (!($entity instanceof $this->entityClass))
			throw new Exception("The entity must be an instance of '{$this->entityClass}'");

		if ($entity->id != null && $this->Any($entity->id)) {
			$this->TryUpdate($entity);
			return $entity->id;
		}
		
		
		$id = $this->New($entity);
		$entity->id = $id;
		return $id;
	}


	#endregion Update and Inserting operations


	#region Deleting operations

	public function Delete(Entity $entity): bool {
		if (!($entity instanceof $this->entityClass))
			throw new Exception("The entity must be an instance of '{$this->entityClass}'");

		$this->DeleteById($entity->id);

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
			throw new Exception("Failed to delete the entity '{$this->entityClass}' with the id '{$this->idField}', nothing has been deleted");
		elseif ($result > 1)
			throw new Exception("An unepected behavior occured while deleting the entity in the context '{$this->entityClass}' with the id '{$this->idField}', {$result} entity have been deleted");

		return true;
	}

	#endregion Deleting operations



}