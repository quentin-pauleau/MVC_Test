<?php
namespace Modules\ORM\DatabaseConverters;

use Modules\UUID\Uuid;
use Modules\UUID\UuidFacade;

final readonly class DatabaseConverterUUID extends DatabaseConverter
{
	private const DEFAULT_VALUE = '';

	/**
	 * Import the value from the database
	 * iso to utf-8 is done by default
	 * @param $data
	 * @param bool $convertToUTF8
	 * @return string
	 */
	public static function Import($data): ?Uuid {
		$data = strval($data);
		try
		{
			$class = UuidFacade::GetUuidClass($data);
		}
		catch (\Throwable $e)
		{
			return null;
		}
		return $class::FromString($data);
	}


	/**
	 * Export the value to the database
	 * @param $data
	 * @param bool $isNullable
	 * @return string
	 */
	public static function Export($data, bool $isNullable = false): string {
		if ($data === null)
			return $isNullable ? self::DATABASE_NULL : self::DEFAULT_VALUE;
		
		if (!Uuid::IsValid($data))
			throw new \Exception("Invalid UUID: $data");

		return strval($data);
	}
}