<?php
namespace Core\Database;

use PDO;
use PDOException;

use Traits\Singleton;

use Core\Database\ListDatabaseQueryParam;


/**
 * A PDO Connection to the database
 */
class DatabaseConnection extends PDO
{
	use Singleton {
		GetInstance as GetConnection;
	}

	private const USER = "root";
	private const PASSWORD = "root";
	private const DB_SERVER = "mysql";
	private const DB_NAME = "nchp_ezged";
	private const HOST = "localhost";
	private const PORT = 3306;
	private const CHARSET = "";

	#region Getters 

	private function GetDSN(): string {
		return self::DB_SERVER.":host=". self::HOST.":". self::PORT .";dbname=". self::DB_NAME; #.";charset=".self::CHARSET;
	}

	
	#endregion

	/**
	 * Etablish connection with the database
	 * @throws \Exception when unable to etablish the connection
	 */
	private function __construct() {
		if (self::isConnected()) {
			return;
		}
		
		try
		{
			parent::__construct(
				self::GetDSN(),
				self::USER,
				self::PASSWORD
			);
			
		}
		catch (PDOException $e) 		{
			throw new DatabaseException("Connection à la base de donnée impossible, erreur : \n<br>$e", 1, $e);
		}
		
		return;
	}

	/**
	 * Automatically close the connection
	 */
	public function __destruct() {
		self::Disconnect();
	}

	
	public function Disconnect (): bool {
		self::$instance = null;
		return true;
	}

	public function IsConnected(): bool {
		return self::$instance != null;
	}

	public function GetOne(string $query, ListDatabaseQueryParam $QueryParams = null): array {
		try
		{
			return DatabaseQueryHandler::GetOne(
				$this,
				$query,
				$QueryParams
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}
	public function GetList(string $query, ListDatabaseQueryParam $QueryParams = null): array {
		try
		{
			return DatabaseQueryHandler::GetList(
				$this,
				$query,
				$QueryParams
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}

	public function InsertOne(string $query, ListDatabaseQueryParam $QueryParams = null): int {
		try
		{
			return DatabaseQueryHandler::insertOne(
				$this,
				$query,
				$QueryParams
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}

	public function InsertList(string $query, ListDatabaseQueryParam $QueryParams = null): int {
		try
		{
			return DatabaseQueryHandler::insertList(
				$this,
				$query,
				$QueryParams
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}

	public function Update(string $query, ListDatabaseQueryParam $QueryParams = null): int {
		try
		{
			return DatabaseQueryHandler::update(
				$this,
				$query,
				$QueryParams
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}

	public function Delete(string $query, ListDatabaseQueryParam $QueryParams = null): int {
		try
		{
			return DatabaseQueryHandler::delete(
				$this,
				$query,
				$QueryParams
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}
}