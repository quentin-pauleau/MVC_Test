<?php
namespace Modules\ORM\DatabaseConverters;

use DateTimeInterface;
use Exception;
use Modules\ORM\Enums\DatabaseTypes;
use Modules\UUID\Uuid;
use ReflectionClass;
use ReflectionProperty;
use Stringable;

final abstract class DatabaseConverterFactory
{
	public static function Create(ReflectionProperty $reflectionProperty, DatabaseTypes|null $databaseType = null): string
	{
		if ($databaseType !== null)
			return self::CreateFromDatabaseType($databaseType);


		$reflectionType = $reflectionProperty->getType()
			?? throw new Exception("The type of the bounded property {$reflectionProperty->name} must be defined.");
		
		$type = $reflectionType->getName();

		if ($reflectionType->isBuiltin())
			return self::CreateFromType($type);

		$reflectionClass = null;

		switch ($type) {
			case 'self':
				# code...
				break;

			case 'parent':
				$reflectionClass = $reflectionProperty->getDeclaringClass()->getParentClass();
				break;

			case 'static': case 'null':
				throw new Exception('');

			default:
				$reflectionClass = new ReflectionClass($type);
		}

		return self::CreateFromObject($type);
	}

	private static function CreateFromDatabaseType(DatabaseTypes $databaseType): string
	{
		return match ($databaseType)
		{
			DatabaseTypes::BOOL => DatabaseConverterBool::class,
			DatabaseTypes::INT => DatabaseConverterInt::class,
			DatabaseTypes::FLOAT => DatabaseConverterFloat::class,

			DatabaseTypes::EMAIL,
			DatabaseTypes::PHONE_NUMBER,
			DatabaseTypes::URL,
			DatabaseTypes::SHORT_TEXT,
			DatabaseTypes::TEXT => DatabaseConverterString::class,

			DatabaseTypes::DATETIME => DatabaseConverterDateTime::class,
			DatabaseTypes::INTERVAL => DatabaseConverterDateInterval::class,

			DatabaseTypes::UUID_TEXT => DatabaseConverterUuid::class,

			default => null,
		};
	}

	private static function CreateFromType(string $type): string
	{
		return match ($type)
		{
			"bool", "boolean" => DatabaseConverterBool::class,
			"int", "integer" => DatabaseConverterInt::class,
			"float", "double" => DatabaseConverterFloat::class,
			"string" => DatabaseConverterString::class,
			"object" => throw new Exception(), // json converter
			"iterable", "array" => throw new Exception(), // json converter

			"resource" => throw new Exception(), // useless
			"resource (closed)" => throw new Exception(), // useless
			"unknown type" => throw new Exception(), // useless
			"callable" => throw new Exception(), // useless
			"NULL" => throw new Exception(), // useless
			default => throw new Exception(),
		};
	}


	private static function CreateFromObject(mixed $class): string
	{
		if (is_class_implementing($class, DateTimeInterface::class))
			return DatabaseConverterDateTime::class;

		if (is_subclass_of($class, Uuid::class))
			return DatabaseConverterUuid::class;

		

		if (is_class_implementing($class, Stringable::class))
			return DatabaseConverterString::class;
		
		throw new Exception();
	}
}