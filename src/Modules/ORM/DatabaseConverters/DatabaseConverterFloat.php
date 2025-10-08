<?php
namespace Modules\ORM\DatabaseConverters;

final readonly class DatabaseConverterFloat extends DatabaseConverter
{
	private const DEFAULT_VALUE = 0;


	/**
	 * Import the value from the database
	 * @param $data
	 * @return float
	 */
	public static function Import($data): float {
		return floatval($data);
	}


	/**
	 * Export the value to the database
	 * @param $data
	 * @param bool $isNullable
	 * @return float|string
	 */
	public static function Export($data, bool $isNullable = false): float|string {
		if ($data === null)
			return $isNullable ? self::DATABASE_NULL : self::DEFAULT_VALUE;

		return floatval($data);
	}
}