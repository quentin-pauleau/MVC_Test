<?php
namespace Modules\ORM\DatabaseConverters;

final readonly class DatabaseConverterString extends DatabaseConverter
{
	private const DEFAULT_VALUE = '';

	/**
	 * Import the value from the database
	 * @param mixed $data
	 * @return string
	 */
	public static function Import(mixed $data): string {
		return strval($data);
	}


	/**
	 * Export the value to the database
	 * @param mixed $data
	 * @param bool $isNullable
	 * @return string
	 */
	public static function Export(mixed $data, bool $isNullable = false): string {
		if ($data === null)
			return $isNullable ? self::DATABASE_NULL : self::DEFAULT_VALUE;
		
		return "`".strval($data)."`";
	}
}