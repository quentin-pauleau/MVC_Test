<?php
namespace Modules\ORM\RecordManager;

use Exception;
use Modules\ORM\Attributes\BindTable;
use Modules\ORM\Attributes\BindField;
use Modules\ORM\Attributes\NullableField;
use ReflectionClass;
use ReflectionProperty;

final class RecordPropertyManager
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

		$Reflexion = (new ReflectionClass($recordClass))->getAttributes(BindTable::class)[0];
		if (!$Reflexion)
			throw new Exception("'$recordClass' has no bind table attribute");

		$tableName = $Reflexion->getArguments()[0]; // 0 = table name arg

		//* get property name
		$this->propertyName = $propertyName;

		$ReflectionProperty = new ReflectionProperty($this->$recordClass, $this->propertyName);
		
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