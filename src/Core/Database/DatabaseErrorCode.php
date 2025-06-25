<?php
namespace Core\Database;

/**
 * Should be changed to an enum in PHP 8+
 */
final class DatabaseErrorCode {

	#region Query Errors
	public const UNKNOWN = 10;

	/**
	 * If the query type is invalid
	 */
	public const INVALID_REQUEST_TYPE = 11;

	/**
	 * If the query preparation failed
	 */
	public const PREPARATION_FAILED = 12;

	/**
	 * Summary of BINDING_FAILED
	 * @var int
	 */
	public const BINDING_FAILED = 13;

	/**
	 * If the query execution failed
	 */
	public const EXECUTION_FAILED = 14;

	/**
	 * If the row selection query (Select) failed
	 */
	public const FETCH_FAILED = 15;

	/**
	 * If the row modification query (Insert | Update | Delete) failed
	 */
	public const MODIFICATION_FAILED = 16;

	/**
	 * If the execution didn't affect any rows
	 */
	public const NOT_FOUND = 17;
	
	#endregion
}