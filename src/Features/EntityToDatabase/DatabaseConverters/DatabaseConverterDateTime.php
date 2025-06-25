<?php
namespace Feature\EntityToDatabase\DatabaseConverters;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Utils\Database\Database;

class DatabaseConverterString extends DatabaseConverter
{
	private const DEFAULT_VALUE = '';
	private const DATE_FORMAT = self::DATE_FORMAT;

	/**
	 * Import the value from the database
	 * iso to utf-8 is done by default
	 * @param $data
	 * @param bool $convertToUTF8
	 * @return string
	 */
	public static function Import($data, $asImmuable = false): DateTime|DateTimeImmutable {
		if ($asImmuable)
			return DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $data);

		return DateTime::createFromFormat(self::DATE_FORMAT, $data);
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

		if ($data instanceof DateTimeInterface)
			return $data->format(self::DATE_FORMAT);
		
		if (is_string($data))
			return $data;
		
		throw new \Exception('Invalid data type for DateTime');
	}
}