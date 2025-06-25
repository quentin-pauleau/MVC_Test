<?php
namespace Feature\EntityToDatabase\DatabaseConverters;

use Traits\StaticClass;


abstract class DatabaseConverter
{
	use StaticClass;

	protected const DATABASE_NULL = 'NULL';

	/**
	 * Import the value from the database
	 * @param $data
	 * @return mixed
	 */
	abstract public static function Import($data): mixed;


	/**
	 * Export the value to the database
	 * @param $data
	 * @param bool $isNullable
	 * @return mixed
	 */
	abstract public static function Export($data, bool $isNullable = false): mixed;
}