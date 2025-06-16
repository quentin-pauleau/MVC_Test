<?php
namespace Feature\EntityToDatabase\DatabaseConverters;


abstract class DatabaseConverter
{
	protected const DATABASE_NULL = 'NULL';

	/**
	 * Import the value from the database
	 * @param $data
	 * @return mixed
	 */
	abstract public function Import($data): mixed;


	/**
	 * Export the value to the database
	 * @param $data
	 * @param bool $isNullable
	 * @return mixed
	 */
	abstract public function Export($data, bool $isNullable = false): mixed;
}