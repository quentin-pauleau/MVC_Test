<?php
namespace Feature\EntityToDatabase\DatabaseConverters;

class DatabaseConverterInt extends DatabaseConverter
{
	private const DEFAULT_VALUE = 0;

	/**
	 * Import the value from the database
	 * @param $data
	 * @return int
	 */
	public function Import($data): int {
		return intval($data);
	}


	/**
	 * Export the value to the database
	 * @param $data
	 * @param bool $isNullable
	 * @return int|string
	 */
	public function Export($data, bool $isNullable = false): int|string {
		if ($data === null)
			return $isNullable ? self::DATABASE_NULL : self::DEFAULT_VALUE;

		return intval($data);
	}
}