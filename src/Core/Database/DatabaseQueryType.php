<?php
namespace Core\Database;

/**
 * Summary of DatabaseQueryType
 */
final class DatabaseQueryType
{
	/**
	 * undefinded query type
	 * @var int
	 */
	public const UNKNOWN = 0;

	/**
	 * Query type used to return the first/only result of a query
	 * @var int
	 */
	public const GET_ONE = 1;

	/**
	 * Query type used to return all result of a query
	 * @var int
	 */
	public const GET_LIST = 2;

	/**
	 * Query type used to return a single collumn of a query
	 * @var int
	 */
	public const GET_COLLUMN = 3;

	/**
	 * Query type used to insert a single row and getting back is given ID
	 * @var int
	 */
	public const INSERT_ONE = 4;

	/**
	 * Query type used to insert a multiple rows and getting back is number of inserted rows
	 * @var int
	 */
	public const INSERT_LIST = 5;

	/**
	 * Query type used to update a multiple row and getting back is number of updated rows
	 * @var int
	 */
	public const UPDATE = 6;

	/**
	 * Query type used to update a multiple row and getting back is number of updated rows
	 * @var int
	 */
	public const DELETE = 7;

	/**
	 * Verify if the given query can be used as a selection query
	 * @param string $query the query to verify
	 * @return bool true if it is a selection query else false
	 */
	public static function IsSelectionQuery(string $query): bool {
		return str_starts_with2($query, "SELECT");
	}

	/**
	 * Verify if the given query can be used as a insertion query
	 * @param string $query the query to verify
	 * @return bool true if it is a insetion query else false
	 */
	public static function IsInsertionQuery(string $query): bool {
		return str_starts_with2($query, "INSERT INTO");
	}

	/**
	 * Verify if the given query can be used as a update query
	 * @param string $query the query to verify
	 * @return bool true if it is a update query else false
	 */
	public static function IsUpdateQuery(string $query): bool {
		return str_starts_with2($query, "UPDATE") && str_contains2($query, "SET");
	}

	/**
	 * Verify if the given query can be used as a deletion query
	 * @param string $query the query to verify
	 * @return bool true if it is a deletion query else false
	 */
	public static function IsDeletionQuery(string $query): bool {
		return str_starts_with2($query, "DELETE FROM");
	}

	public static function GetQueryType(string $query): int {
		if (self::IsSelectionQuery($query))
			return DatabaseQueryType::GET_ONE;
		
		if (self::IsInsertionQuery($query))
			return DatabaseQueryType::INSERT_LIST;
		
		if (self::IsUpdateQuery($query))
			return DatabaseQueryType::UPDATE;
		
		if (self::IsDeletionQuery($query))
			return DatabaseQueryType::DELETE;
		
		return DatabaseQueryType::UNKNOWN;
	}
}