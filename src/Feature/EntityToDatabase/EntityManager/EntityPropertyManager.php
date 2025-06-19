<?php
namespace Feature\EntityToDatabase\EntityManager;

use Exception;
use Feature\EntityToDatabase\Attributes\BindField;
use Feature\EntityToDatabase\Attributes\BindTable;
use Feature\EntityToDatabase\Attributes\NullableField;
use Models\Entities\Entity;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionProperty;

final class EntityPropertyManager
{
	private string $entityClass;
	private string $converterClass;
	private string $propertyName;
	private string|null $propertySetterName;
	private string|null $propertyGetterName;
	private string $fieldName;
	private string $fieldType;
	private bool $fieldIsNullable;

	
	public function GetEntityClass(): string { return $this->entityClass; }
	public function GetConverterClass(): string { return $this->converterClass; }
	public function GetPropertyName(): string { return $this->propertyName; }
	public function GetFieldName(): string { return $this->fieldName; }
	public function GetFieldType(): string { return $this->fieldType; }
	public function GetFieldIsNullable(): bool { return $this->fieldIsNullable; }


	public function __construct(string $entityClass, string $propertyName) {
		if (!is_subclass_of($entityClass, Entity::class))
			throw new Exception("'$entityClass' does not extend 'Entity'");
		
		$this->entityClass = $entityClass;

		$Reflexion = (new ReflectionClass($entityClass))->getAttributes(BindTable::class)[0];
		if (!$Reflexion)
			throw new Exception("'$entityClass' has no bind table attribute");

		$tableName = $Reflexion->getArguments()[0]; // 0 = table name arg

		//* get property name
		$this->propertyName = $propertyName;

		$ReflectionProperty = new ReflectionProperty($this->$entityClass, $this->propertyName);
		
		$bind = $ReflectionProperty->getAttributes(BindField::class)[0];

		//* get the field name
		$this->fieldName = strtoupper($bind->getArguments()[0]); // 0 = field name arg

		if (!str_starts_with($this->fieldName, strtoupper($tableName)))
			$this->fieldName = "$tableName\_$this->fieldName";

		//* get the field type
		$this->fieldType = $bind->getArguments()[1]; // 1 = type arg

		//* get if the field is nullable or not
		$nullableAttribute = $ReflectionProperty->getAttributes(NullableField::class);
		$this->fieldIsNullable = !$nullableAttribute
			? $nullableAttribute[0]->getArguments()[0] 
			: $this->fieldIsNullable =  $bind->getArguments()[2]; // 2 = is nullable arg


		//* get the converter class coresponding to the right type
		$this->converterClass = "DatabaseConverter$this->fieldType";
		
		if (!file_exists("./DatabaseConverters/$this->converterClass.php"))
			throw new Exception("No converter found for type '$this->fieldType'");

		require_once "./DatabaseConverters/$this->converterClass.php";

		//* get the property's setter name
		if (method_exists($Entity, "Set$property"))
			$this->propertySetterName = "set$property";
		elseif (method_exists($Entity, "Set_$property"))
			$this->propertySetterName = "set_$property";
		else
			$this->propertySetterName = null;

		//* get the property's getter name
		if (method_exists($Entity, "Get$property"))
			$this->propertyGetterName = "get$property";
		elseif (method_exists($Entity, "Get_$property"))
			$this->propertyGetterName = "get_$property";
		else
			$this->propertyGetterName = null;
	}


	public function ImportValue(Entity $entity, mixed $value): bool {
		if (!($entity instanceof $this->entityClass))
			throw new Exception("Invalid entity type, entity must be of type '$this->entityClass'");
		
		$value = $this->converterClass::Import($value);
		
		if ($this->propertyGetterName === null)
			$entity->{$this->propertyName} = $value; 
		else
			$entity->{$this->propertySetterName}($value);
		
		return true;
	}

	public function ExportValue(Entity $entity): mixed {
		if (!($entity instanceof $this->entityClass))
			throw new Exception("Invalid entity type, entity must be of type '$this->entityClass'");
		
		$value = $this->propertyGetterName === null 
			? $entity->{$this->propertyName} 
			: $entity->{$this->propertyGetterName}();
		
		return $this->converterClass::Export($value, $this->fieldIsNullable);
	}
}