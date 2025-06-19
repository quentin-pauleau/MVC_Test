<?php
namespace Feature\EntityToDatabase\DatabaseConverters;

class DatabaseConverterString extends DatabaseConverter
{
	private const DEFAULT_VALUE = '';

	/**
	 * Import the value from the database
	 * iso to utf-8 is done by default
	 * @param $data
	 * @param bool $convertToUTF8
	 * @return string
	 */
	public static function Import($data, $convertToUTF8 = true): string {
		if ($convertToUTF8)
			return Convert_encoding_to_utf8(strval($data));
		
		return strval($data);
	}


	/**
	 * Export the value to the database
	 * @param $data
	 * @param bool $isNullable
	 * @return string
	 */
	public static function Export($data, bool $isNullable = false, bool $convertToIso = true): string {
		if ($data === null)
			return $isNullable ? self::DATABASE_NULL : self::DEFAULT_VALUE;

		if ($convertToIso)
			return Convert_encoding_to_iso(strval($data));
		
		return strval($data);
	}
}