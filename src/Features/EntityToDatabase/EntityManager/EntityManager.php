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


	#region CRUD operations

	public function GetById(int $id): Entity|null {
		$item = [];

		$query = 'SELECT ';

		$query .= $this->GetFieldSelection();

		//* add the 
		$query .= " FROM `$this->tableName` WHERE `$this->idField` = :id";

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
	public function GetAll(): ListEntity {
		$query = 'SELECT ';

		$query .= $this->GetFieldSelection();

		$query .= " FROM `$this->tableName`";

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
		$query = "SELECT `$this->idField` WHERE `$this->idField` = :id";

		$item = $this->connection->GetOne(
			$query,
			new ListDatabaseQueryParam(
				new DatabaseQueryParam(':id', $id, PDO::PARAM_INT)
			)
		);

		return $item != [];
	}


	/**
	 * Return true if the entity has been updated
	 * @param \Models\Entities\Entity $entity
	 * @return void
	 */
	public function Update(Entity $entity): bool {
		if (!($entity instanceof $this->entityClass))
			throw new Exception("The entity must be an instance of '$this->entityClass'");

		$query = "UPDATE $this->tableName SET ";

		$query = $this->GetFieldUpdateSelection();
		$query = " WHERE `$this->idField` = :id";

		$result = $this->connection->Update(
			$query,
			$this->GetFieldUpdateParameters($entity)
		);

		return $result === 1;
	}

	public function Insert(Entity $entity): int {
		if (!($entity instanceof $this->entityClass))
			throw new Exception("The entity must be an instance of '$this->entityClass'");

		$query = "INSERT INTO $this->tableName ";

		$id = $this->connection->InsertOne(
			$query,
			$this->GetFieldUpdateParameters($entity)
		);

		return $id;
	}

	#endregion CRUD operations
}