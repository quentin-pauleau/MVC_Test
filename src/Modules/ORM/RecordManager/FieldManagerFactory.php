<?php
namespace Modules\ORM\RecordManager;

use Exception;
use Modules\ORM\Binding\BindAbstractField;
use Modules\ORM\Binding\BindField;
use Modules\ORM\Binding\BindForeignField;
use Modules\ORM\Binding\BindPrimaryField;
use Modules\ORM\Binding\BindTable;
use Modules\ORM\Binding\BindUniqueField;
use ReflectionClass;
use ReflectionProperty;


/**
 * Factory to create the correct field manager based on a property.
 *
 * @author <NAME> <<EMAIL>>
 * @experimental
 */
final abstract class FieldManagerFactory 
{
	/**
	 * Summary of Create
	 * @param string $recordClass
	 * @param string $propertyName
	 * @throws \Exception 
	 * @return FieldManager|ForeignFieldManager|PrimaryFieldManager|UniqueFieldManager
	 * @return null returned when the property isnt bound to a field
	 */
	public static function Create(
		string $recordClass,
		string $propertyName,
	): FieldManager|ForeignFieldManager|PrimaryFieldManager|UniqueFieldManager|null
	{
		$ReflectionProperty = new ReflectionProperty($recordClass, $propertyName);

		if ($ReflectionProperty->getAttributes(BindAbstractField::class) === [])
			return null;

		$bindFieldCount = count($ReflectionProperty->getAttributes(BindField::class));
		$bindUniqueFieldCount = count($ReflectionProperty->getAttributes(BindUniqueField::class));
		$bindPrimaryFieldCount = count($ReflectionProperty->getAttributes(BindPrimaryField::class));
		$bindForeignFieldCount = count($ReflectionProperty->getAttributes(BindForeignField::class));

		if (
			$bindFieldCount === 0 &&
			$bindUniqueFieldCount === 0 &&
			$bindPrimaryFieldCount === 0 &&
			$bindForeignFieldCount === 0
		)
			throw new Exception("No bind found for property {$propertyName} of the record class {$recordClass}.");
		
		if ($bindFieldCount + $bindUniqueFieldCount + $bindPrimaryFieldCount + $bindForeignFieldCount > 1)
			throw new Exception("The property {$propertyName} can not be bound to multiple fields in the record class {$recordClass}.");
		
		return match (1)
		{
			$bindFieldCount => new FieldManager($recordClass, $propertyName),
			$bindUniqueFieldCount => new UniqueFieldManager($recordClass, $propertyName),
			$bindPrimaryFieldCount => new PrimaryFieldManager($recordClass, $propertyName),
			$bindForeignFieldCount => new ForeignFieldManager($recordClass, $propertyName),
			default =>throw new Exception("Unknown field bind for property {$propertyName} of the record class {$recordClass}."),
		};
	}
}