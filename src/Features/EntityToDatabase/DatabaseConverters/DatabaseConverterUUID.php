<?php
namespace Feature\EntityToDatabase\DatabaseConverters;

use Core\UUID;

class DatabaseConverterUUID extends DatabaseConverter
{
	private const DEFAULT_VALUE = '';

	/**
	 * Import the value from the database
	 * iso to utf-8 is done by default
	 * @param $data
	 * @param bool $convertToUTF8
	 * @return string
	 */
	public static function Import($data): UUID {
		return UUID::FromString(Convert_encoding_to_utf8(strval($data)));
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
		
		if ($data instanceof UUID)
			return Convert_encoding_to_iso($data->GetUUID());
		
		if (!UUID::IsValid($data))
			throw new \Exception("Invalid UUID: " . strval($data));

		return Convert_encoding_to_iso($data);
	}
}