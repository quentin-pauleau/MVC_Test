<?php
namespace Core\Database;

use Core\Database\ListDatabaseQueryParam;
use Core\Database\DatabaseConnection;


/**
 * A Lazy Connection to the database
 */
class Database
{
	/**
	 * MySQL date format
	 * @var string
	 */
	public const DATE_FORMAT = 'Y-m-d H:i:s';


	public function __construct() { }

	private function GetLazyConnection(): DatabaseConnection {
		return DatabaseConnection::GetConnection();
	}


	public function IsConnected(): bool {
		return DatabaseConnection::GetConnection()->IsConnected();
	}


	/**
	 * Execute a selection query and fetch the 1st row
	 * @param string $query
	 * @param \Core\Database\ListDatabaseQueryParam|null $QueryParams
	 * @return array
	 */
	public function GetOne(string $query, ListDatabaseQueryParam $QueryParams = null): array {
		try
		{
			return DatabaseQueryHandler::GetOne(
				$this->GetLazyConnection(),
				$query,
				$QueryParams
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	/**
	 * Execute a selection query and fetch all rows
	 * @param string $query
	 * @param \Core\Database\ListDatabaseQueryParam|null $QueryParams
	 * @return array
	 */
	public function GetList(string $query, ListDatabaseQueryParam $QueryParams = null): array {

		try
		{
			return DatabaseQueryHandler::GetList(
				$this->GetLazyConnection(),
				$query,
				$QueryParams
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	/**
	 * Execute a insertion query, with one insertion
	 * @param string $query
	 * @param \Core\Database\ListDatabaseQueryParam|null $QueryParams
	 * @return int the id of the inserted row
	 */
	public function InsertOne(string $query, ListDatabaseQueryParam $QueryParams = null): int {

		try
		{
			return DatabaseQueryHandler::InsertOne(
				$this->GetLazyConnection(),
				$query,
				$QueryParams
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	/**
	 * Execute a insertion query, with multiples insertion
	 * @param string $query the insertion query
	 * @param \Core\Database\ListDatabaseQueryParam|null $QueryParams
	 * @return int the total of inserted rows
	 */
	public function InsertList(string $query, ListDatabaseQueryParam $QueryParams = null): int {

		try
		{
			return DatabaseQueryHandler::insertList(
				$this->GetLazyConnection(),
				$query,
				$QueryParams
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}

	
	/**
	 * Execute a update query
	 * @param string $query the update query
	 * @param \Core\Database\ListDatabaseQueryParam|null $QueryParams
	 * @return int the total of updated rows
	 */
	public function Update(string $query, ListDatabaseQueryParam $QueryParams = null): int {

		try
		{
			return DatabaseQueryHandler::Update(
				$this->GetLazyConnection(),
				$query,
				$QueryParams
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}

	/**
	 * Execute a deletion query
	 * @param string $query the deletion query
	 * @param \Core\Database\ListDatabaseQueryParam|null $QueryParams
	 * @return int the total of deleted rows
	 */
	public function Delete(string $query, ListDatabaseQueryParam $QueryParams = null): int {

		try
		{
			return DatabaseQueryHandler::delete(
				$this->GetLazyConnection(),
				$query,
				$QueryParams
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	/**
	 * Begin a new transaction
	 * @return bool true on success, false on failure
	 */
	public function BeginTransaction(): bool {
		return $this->GetLazyConnection()->beginTransaction();
	}


	/**
	 * Commit all changed made in the active transaction
	 * @return bool true on success, false on failure
	 */
	public function CommitTransaction(): bool {
		return $this->GetLazyConnection()->commit();
	}


	/**
	 * Rollback all changed made in the active transaction
	 * @return bool true on success, false on failure
	 */
	public function RollBackTransaction(): bool {
		return $this->GetLazyConnection()->rollBack();
	}


	/**
	 * Check if a transaction is active
	 * @return bool true if a transaction is currently active, and false if not.
	 */
	public function IsInTransaction(): bool {
		return $this->GetLazyConnection()->inTransaction();
	}
}