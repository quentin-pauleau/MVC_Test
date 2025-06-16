<?php
namespace Utils\Database;

use Exception;
use PDO;
use PDOStatement;
use PDOException;

use Utils\Database\DatabaseQueryType;
use Utils\Database\DatabaseException;
use Utils\Database\DatabaseQueryParam;
use Utils\Database\ListDatabaseQueryParam;

use Traits\StaticClass;

class DatabaseQueryHandler
{
	use StaticClass;

	public static function Execute(PDO $Connection, string $query, 
			DatabaseQueryType $QueryType = DatabaseQueryType::UNKNOWN, ListDatabaseQueryParam $QueryParams = null) {
		try
		{
			switch ($QueryType) {
				case DatabaseQueryType::UNKNOWN:
					$QueryType = DatabaseQueryType::getQueryType($query);
				
				case DatabaseQueryType::GET_ONE:
					return self::GetOne($Connection, $query, $QueryParams);
				
				case DatabaseQueryType::GET_LIST:
					return self::GetList($Connection, $query, $QueryParams);
				
				case DatabaseQueryType::GET_COLLUMN:
					return self::GetCollumn($Connection, $query, $QueryParams);
				
				case DatabaseQueryType::INSERT_ONE:
					return self::InsertOne($Connection, $query, $QueryParams);
				
				case DatabaseQueryType::INSERT_LIST:
					return self::InsertList($Connection, $query, $QueryParams);
				
				case DatabaseQueryType::UPDATE:
					return self::Update($Connection, $query, $QueryParams);
				
				case DatabaseQueryType::DELETE:
					return self::Delete($Connection, $query, $QueryParams);
				
				default:
					throw new Exception("Ce type de requête n'est pas supporté");
			}
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	/**
	 * Summary of prepare
	 * @param PDO $Connection
	 * @param string $query
	 * @throws DatabaseException
	 * @return PDOStatement
	 */
	public static function prepare(PDO $Connection, string $query): PDOStatement { 
		try
		{
			$prep = $Connection->prepare($query);
		}
		catch (PDOException $e) 		{
			throw new DatabaseException(
				"La péparation a échouée, exception \n<br> $e",
				DatabaseQueryType::UNKNOWN, 
				DatabaseErrorCode::PREPARATION_FAILED,
				$e
			);
		}
		
		if ($prep === false) {
			throw new DatabaseException(
				"La péparation a échouée, exception inconnue",
				DatabaseQueryType::UNKNOWN,
				DatabaseErrorCode::PREPARATION_FAILED,
			);
		}
		
		return $prep;
	}


	protected static function BindParamList(PDOStatement $Statement, ListDatabaseQueryParam $QueryParams = null): bool {
		foreach ($QueryParams as $QueryParam) {
			try
		{
				self::bindParamOne($Statement, $QueryParam);
			}
		catch (DatabaseException $e)
		{
				throw $e;
			}
		}
		return true;
	}


	protected static function BindParamOne(PDOStatement $Statement, DatabaseQueryParam $QueryParam): bool {
		if (!$QueryParam->isBindPossible($Statement)) {
			throw new DatabaseException(
				"Impossible to bind the parametter \"$QueryParam->bind\" in the statement",
				DatabaseQueryType::UNKNOWN,
				DatabaseErrorCode::BINDING_FAILED,
			);
		}

		if (!$QueryParam->bindAsValue($Statement)) {
			throw new DatabaseException(
				"The binding failed",
				DatabaseQueryType::UNKNOWN,
				DatabaseErrorCode::BINDING_FAILED,
			);
		}
		
		return true;
	}


	protected static function sendQuery(PDO $Connection, string &$query, ListDatabaseQueryParam $QueryParams = null): PDOStatement {
		try
		{
			$Statement = self::prepare($Connection, $query);

			if ($QueryParams != null && !$QueryParams->IsEmpty()) {
				self::bindParamList($Statement, $QueryParams);
			}

			self::handleExecution($Statement);
			return $Statement;
		}
		catch (DatabaseException $e)
		{
			throw new DatabaseException(
				$e->getMessage(),
				DatabaseQueryType::UNKNOWN,
				$e->getCode(),
				$e
			);
		}
	}


	/**
	 * Summary of GetOne
	 * @param PDO $Connection
	 * @param string $query
	 * @throws DatabaseException
	 * @return array
	 */
	public static function GetOne(PDO $Connection, string $query, ListDatabaseQueryParam $QueryParams = null): array {
		if (!DatabaseQueryType::isSelectionQuery($query)) {
			throw new DatabaseException(
				"Requête invalide, ce n'est pas une requête de selection",
				DatabaseQueryType::UNKNOWN,
				DatabaseErrorCode::INVALID_REQUEST_TYPE,
			);
		}

		try
		{
			$Statement = self::SendQuery($Connection, $query, $QueryParams);
			return self::HandleFetch($Statement);
		}
		catch (DatabaseException $e)
		{
			throw new DatabaseException(
				$e, 
				DatabaseQueryType::GET_ONE, 
				$e->getCode(), 
				$e
			);
		}
	}


	/**
	 * Summary of GetList
	 * @param PDO $Connection
	 * @param string $query
	 * @throws \Exception
	 * @return array
	 */
	public static function GetList(PDO $Connection, string $query, ListDatabaseQueryParam $QueryParams = null): array {
		if (!DatabaseQueryType::IsSelectionQuery($query)) {
			throw new DatabaseException(
				"Requête invalide, ce n'est pas une requête de selection",
				DatabaseQueryType::GET_LIST,
				DatabaseErrorCode::INVALID_REQUEST_TYPE,
			);
		}

		try
		{
			$Statement = self::sendQuery($Connection, $query, $QueryParams);
			return self::handleFetchAll($Statement);
		}
		catch (DatabaseException $e)
		{
			throw new DatabaseException(
				$e,
				DatabaseQueryType::GET_LIST,
				$e->getCode(),
				$e
			);
		}
	}

	
	/**
	 * Summary of handleGetCollumn
	 * @param PDO $Connection
	 * @param string $query
	 * @throws DatabaseException
	 * @return array
	 */
	public static function getCollumn(PDO $Connection, string $query, ListDatabaseQueryParam $QueryParams = null): array {
		if (!DatabaseQueryType::isSelectionQuery($query)) {
			throw new DatabaseException(
				"Requête invalide, ce n'est pas une requête de selection",
				DatabaseQueryType::GET_COLLUMN, 
				DatabaseErrorCode::INVALID_REQUEST_TYPE
			);
		}

		try
		{
			$Statement = self::sendQuery($Connection, $query, $QueryParams);
			return self::handleFetchCollumn($Statement);
		}
		catch (DatabaseException $e)
		{
			throw new DatabaseException(
				$e, 
				DatabaseQueryType::GET_COLLUMN, 
				$e->getCode(), 
				$e
			);
		}
	}


	/**
	 * Summary of insertOne
	 * @param PDO $Connection
	 * @param string $query
	 * @throws DatabaseException
	 * @return int
	 */
	public static function InsertOne(PDO $Connection, string $query, ListDatabaseQueryParam $QueryParams = null): int {
		if (!DatabaseQueryType::IsInsertionQuery($query)) {
			throw new DatabaseException(
				"Requête invalide, ce n'est pas une requête de insertion",
				DatabaseQueryType::INSERT_ONE,
				DatabaseErrorCode::INVALID_REQUEST_TYPE
			);
		}

		try
		{
			self::sendQuery($Connection, $query, $QueryParams);
			return $Connection->lastInsertId();
		}
		catch (DatabaseException $e)
		{
			throw new DatabaseException(
				$e->getMessage(), 
				DatabaseQueryType::INSERT_ONE, 
				$e->getCode(),
				$e
			);
		}
	}
	
	
	/**
	 * Summary of insert
	 * @param PDO $Connection
	 * @param string $query
	 * @throws DatabaseException
	 * @return int
	 */
	public static function InsertList(PDO $Connection, string $query, ListDatabaseQueryParam $QueryParams = null): int {
		if (!DatabaseQueryType::isInsertionQuery($query)) {
			throw new DatabaseException(
				"Requête invalide, ce n'est pas une requête de insertion",
				DatabaseQueryType::INSERT_LIST,
				DatabaseErrorCode::INVALID_REQUEST_TYPE
			);
		}
		
		try
		{
			$Statement = self::sendQuery($Connection, $query, $QueryParams);
			return self::handleRowModification($Statement);
		}
		catch (DatabaseException $e)
		{
			throw new DatabaseException(
				$e, 
				DatabaseQueryType::INSERT_LIST, 
				$e->getCode(),
				$e
			);
		}
	}
	

	/**
	 * Summary of update
	 * @param PDO $Connection
	 * @param string $query
	 * @throws DatabaseException
	 * @return int
	 */
	public static function Update(PDO $Connection, string $query, ListDatabaseQueryParam $QueryParams = null): int {
		if (!DatabaseQueryType::IsUpdateQuery($query)) {
			throw new DatabaseException(
				"Requête invalide, ce n'est pas une requête de mise à jour",
				DatabaseQueryType::UPDATE,
				DatabaseErrorCode::INVALID_REQUEST_TYPE
			);
		}
		
		try
		{
			$Statement = self::SendQuery($Connection, $query, $QueryParams);
			return self::HandleRowModification($Statement);
		}
		catch (Exception $e) 		{
			throw new DatabaseException(
				$e, 
				DatabaseQueryType::UPDATE, 
				$e->getCode(), 
				$e
			);
		}
	}


	/**
	 * Summary of delete
	 * @param PDO $Connection
	 * @param string $query
	 * @throws DatabaseException
	 * @return int
	 */
	public static function Delete(PDO $Connection, string $query, ListDatabaseQueryParam $QueryParams = null): int {
		if (!DatabaseQueryType::isDeletionQuery($query)) {
			throw new DatabaseException(
				"Requête invalide, ce n'est pas une requête de suppression", 
				DatabaseQueryType::DELETE, 
				DatabaseErrorCode::INVALID_REQUEST_TYPE, 
			);
		}
		
		try
		{
			$Statement = self::SendQuery($Connection, $query, $QueryParams);
			return self::HandleRowModification($Statement);
		}
		catch (Exception $e) 		{
			throw new DatabaseException(
				$e, 
				DatabaseQueryType::DELETE, 
				$e->getCode()
			);
		}
	}


	/**
	 * Summary of handleExecution
	 * @param PDOStatement $Statement
	 * @throws DatabaseException
	 * @return void
	 */
	protected static function handleExecution(PDOStatement $Statement): bool {
		try
		{
			return $Statement->execute();
		}
		catch (PDOException $e) 		{
			throw new DatabaseException(
				"La requête a échouée, exception : \n<br> $e", 
				DatabaseQueryType::UNKNOWN, 
				DatabaseErrorCode::EXECUTION_FAILED, 
				$e
			);
		}
	}


	/**
	 * Summary of handleFetchAll
	 * @param PDOStatement $Statement
	 * @throws DatabaseException
	 * @return array
	 */
	protected static function handleFetchAll(PDOStatement $Statement): array {
		$datas = $Statement->fetchAll(PDO::FETCH_ASSOC);
		
		if ($datas === false) {
			throw new DatabaseException(
				"Impossible de récupérer les données de la base de donnée",
				DatabaseQueryType::GET_LIST,
				DatabaseErrorCode::FETCH_FAILED
			);
		}
		
		return $datas;
	}


	/**
	 * Summary of handleFetch
	 * @param PDOStatement $Statement
	 * @throws DatabaseException
	 * @return array
	 */
	protected static function handleFetch(PDOStatement $Statement): array {
		$data = $Statement->fetch(PDO::FETCH_ASSOC);

		if ($data === false) {
			return [];
		}
		
		return $data;
	}


	/**
	 * Summary of handleFetchCollumn
	 * @param PDOStatement $Statement
	 * @throws DatabaseException
	 * @return array
	 */
	protected static function HandleFetchCollumn(PDOStatement $Statement): array {
		$data = $Statement->fetchColumn();

		if ($data === false) {
			throw new DatabaseException(
				"Impossible de récupérer les données de la base de donnée",
				DatabaseQueryType::GET_COLLUMN,
				DatabaseErrorCode::FETCH_FAILED
			);
		}

		$datas = [];
		array_push($datas, $data);

		while ($data != false) {
			$data = $Statement->fetchColumn();
			array_push($datas, $data);
		}
		
		return $datas;
	}


	/**
	 * Summary of handleRowModification
	 * @param PDOStatement $Statement
	 * @throws DatabaseException
	 * @return int number of affected rows
	 */
	protected static function HandleRowModification(PDOStatement $Statement): int {
		return $Statement->rowCount();
	}
}