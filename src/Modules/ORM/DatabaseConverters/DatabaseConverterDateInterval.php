<?php
namespace Modules\ORM\DatabaseConverters;

use DateInterval;
/**
 * Converter in charged of importing and exporting Date interval with the database
 * @experimental
 */
final readonly class DatabaseConverterDateInterval extends DatabaseConverter
{
	private const DEFAULT_VALUE = 'NOW()';

	/**
	 * Import the value from the database
	 * @param $data
	 * @return string
	 */
	public static function Import(mixed $data): DateInterval {
		return DateInterval::createFromDateString($data);
	}


	/**
	 * Export the value to the database
	 * @param $data
	 * @param bool $isNullable
	 * @return string
	 */
	public static function Export(mixed $data, bool $isNullable = false): string {
		if ($data === null)
			return $isNullable ? self::DATABASE_NULL : self::DEFAULT_VALUE;

		if ($data instanceof DateInterval)
			return $data->format('%Y-%m-%d %H:%I:%S');

		if (is_string($data))
			return $data;
		
		throw new \Exception('Invalid data type for DateTime');
	}
}