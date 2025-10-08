<?php
namespace Core\Database;

use Traits\StaticClass;
use PDO;


class DatabaseQueryParamTypes
{
	use StaticClass;

	/**
	 * Convert to PDO::PARAM_NULL
	 * @var int
	 */
	public const NULL = 0;

	/**
	 * Convert to PDO::PARAM_INT
	 * @var int
	 */
	public const INT = 1;

	/**
	 * Convert to PDO::PARAM_STR while checking if value is a int/float
	 * @var int
	 */
	public const FLOAT = 2;

	/**
	 * Convert to PDO::PARAM_STR
	 * @var int
	 */
	public const STRING = 3; // to PDO::PARAM_STR

	/**
	 * Convert to PDO::PARAM_BOOL
	 * @var int
	 */
	public const BOOL = 4;

	/**
	 * Convert to PDO::PARAM_STR while checking if value is : 
	 * - datetime
	 * - timestamp
	 * - y-m-d H:i:s string
	 * @var int
	 */
	public const DATETIME = 5;

	/**
	 * Convert to PDO::PARAM_STR while checking if value is a UUID
	 * @var int
	 */
	public const UUID = 6;

	/**
	 * Convert to PDO::PARAM_STR while checking if value is a : 
	 * - datetime
	 * - timestamp
	 * - y-m-d H:i:s string
	 * @var int
	 */
	public const TIMESTAMP = 7;

	/**
	 * Convert to PDO::PARAM_STR while checking if value is a JSON
	 * @var int
	 */
	public const JSON = 8;
}