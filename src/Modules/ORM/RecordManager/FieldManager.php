<?php
namespace Modules\ORM\RecordManager;

use Exception;
use Modules\ORM\Binding\BindTable;
use Modules\ORM\Binding\BindField;
use ReflectionClass;
use ReflectionProperty;

final readonly class FieldManager extends AbstractFieldManager
{
	private string $recordClass;
	private string $converterClass;
	private string $propertyName;
	private string|null $propertySetterName;
	private string|null $propertyGetterName;
	private string $fieldName;
	private string $fieldType;
	private bool $fieldIsNullable;

	
	public function GetEntityClass(): string { return $this->recordClass; }
	public function GetConverterClass(): string { return $this->converterClass; }
	public function GetPropertyName(): string { return $this->propertyName; }
	public function GetFieldName(): string { return $this->fieldName; }
	public function GetFieldType(): string { return $this->fieldType; }
	public function GetFieldIsNullable(): bool { return $this->fieldIsNullable; }


	public function __construct(string $recordClass, string $propertyName) {
		if (!is_subclass_of($recordClass, DatabaseRecord::class))
			throw new Exception("'$recordClass' does not extend 'Entity'");
		
		$this->recordClass = $recordClass;

		$Reflexion = (new ReflectionClass($recordClass))->getAttributes(BindTable::class)[0]
			?? throw new Exception("'$recordClass' has no bind table attribute");

		$tableName = $Reflexion->getArguments()[0]; // 0 = table name arg

		//* get property name
		$this->propertyName = $propertyName;

		$ReflectionProperty = new ReflectionProperty($this->$recordClass, $this->propertyName);
		
		$bind = $ReflectionProperty->getAttributes(BindField::class)[0]
			?? throw new Exception("'$recordClass::$propertyName' has no bind field attribute");

		//* get the field name
		$this->fieldName = strtoupper($bind->getArguments()[0]); // 0 = field name arg

		if (!str_starts_with($this->fieldName, strtoupper($tableName)))
			$this->fieldName = "{$tableName}_{$this->fieldName}";

		//* get the field type
		$this->fieldType = $bind->getArguments()[1]; // 1 = type arg

		


		//* get the converter class coresponding to the right type
		$ConverterReflection = (new ReflectionClass($this->fieldType));

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

	private function InitConverter() {

	}


	public function ImportValue(DatabaseRecord $record, mixed $value): bool {
		if (!($record instanceof $this->recordClass))
			throw new Exception("Invalid record type, record must be of type '$this->recordClass'");
		
		$value = $this->converterClass::Import($value);
		
		if ($this->propertyGetterName === null)
			$record->{$this->propertyName} = $value; 
		else
			$record->{$this->propertySetterName}($value);
		
		return true;
	}

	public function ExportValue(DatabaseRecord $record): mixed {
		if (!($record instanceof $this->recordClass))
			throw new Exception("Invalid record type, record must be of type '$this->recordClass'");
		
		$value = $this->propertyGetterName === null 
			? $record->{$this->propertyName} 
			: $record->{$this->propertyGetterName}();
		
		return $this->converterClass::Export($value, $this->fieldIsNullable);
	}
}