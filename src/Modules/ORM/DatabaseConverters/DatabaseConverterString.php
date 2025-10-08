<?php
namespace Modules\ORM\DatabaseConverters;

final readonly class DatabaseConverterString extends DatabaseConverter
{
	private const DEFAULT_VALUE = '';

	/**
	 * Import the value from the database
	 * @param $data
	 * @return string
	 */
	public static function Import($data): string {
		return strval($data);
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
		
		return strval($data);
	}
}