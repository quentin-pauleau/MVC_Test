<?php
namespace Modules\DatabaseConnection;

use Exception;
use PDO;
use Utils\Database\DatabaseTransaction;
use Modules\DatabaseConnection\DatabaseQueryBuilder\DatabaseQueryBuilder;

class DatabaseConnection
{
	private static ?PDO $pdo = null;

	private string $host = 'localhost';
	private string $username = 'root';

	private string $password = '';

	private string $dbName = 'database';

	private int $port = 3306;


	public function __construct()
	{
		
	}

	protected function GetConnection(): PDO
	{
		$this->connect();
		return self::$pdo;
	}


	public function Connect(): bool
	{
		if (self::$pdo)
			return true;

		$this->pdo = new PDO('mysql:host=localhost;dbname=database', 'root', '');

		return true;
	}

	public function Disconnect(): void
	{
		self::$pdo = null;
	}

	public function IsConnected(): bool
	{
		return self::$pdo !== null;
	}

	public function NewTransaction(): DatabaseTransaction {
		return new DatabaseTransaction(self::$pdo);
	}

	public function Transact(callable $callback): mixed
	{
		$t = new DatabaseTransaction($this->pdo);

		try
		{
			$result = $callback();
		}
		catch(Exception $e)
		{
			$t->Rollback();
		}

		$t->Commit();

		return $result;
	}


	private function Update(): void {

	}


	public function SaveChanges(): void {
		$this->pdo->commit();
		$this->pdo->beginTransaction();
	}


	public function CancelChanges(): void {
		$this->pdo->rollBack();
		$this->pdo->beginTransaction();
	}


	public function Query(): DatabaseQueryBuilder {
		return new DatabaseQueryBuilder;
	}


	public function Excute($query) {
		
		if (is_string($query)) {
			switch (true) {
				case str_starts_with($query, "SELECT"):
					break;
				
				case str_starts_with($query, "INSERT"):
					break;

				case str_starts_with($query, "UPDATE"):
					break;
				
				case str_starts_with($query, "DELETE"):
					break;
				
				case str_starts_with($query, "CREATE"):
					break;
				
				case str_starts_with($query, "DROP"):
					break;
				
				case str_starts_with($query, "ALTER"):
					break;
				
				default:
					throw new Exception("Invalid query");
			}
		}
	}
}