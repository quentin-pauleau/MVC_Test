<?php
namespace Modules\DatabaseConnection;

use Exception;
use PDO;
use Utils\Database\DatabaseTransaction;
use Modules\DatabaseConnection\QueryBuilder\QueryBuilder;

class DatabaseConnection
{

	/**
	 * @var self[]
	 */
	private static array $connections;


	private static ?self $defaultConnection;

	private ?PDO $pdo = null;

	private string $host = 'localhost';
	private string $username = 'root';

	private string $password = '';

	private string $databaseName;

	private int $port = 3306;


	private function __construct(string $name)
	{
		if (isset(self::$connections[$name]))
			throw new Exception("Connection already exists");

		self::$connections[$name] = $this;
	}

	protected function GetPDO(): PDO
	{
		$this->connect();
		return $this->pdo;
	}


	public function SetAsDefaultConnection(): void
	{
		self::$defaultConnection = $this;
	}


	public static function GetConnection(string|null $name = null): ?self
	{
		if ($name === null)
			return self::$defaultConnection;

		if (!isset(self::$connections[$name]))
			throw new Exception("Connection not found");

		return self::$connections[$name];
	}


	public function Connect(): bool
	{
		if ($this->pdo)
			return true;

		$this->pdo = new PDO(
			"mysql:host={$this->host}:{$this->port};dbname={$this->databaseName}", 
			$this->username, 
			$this->password,
		);

		return true;
	}

	public function Disconnect(): void
	{
		$this->pdo = null;
	}

	public function IsConnected(): bool
	{
		return $this->pdo !== null;
	}

	public function NewTransaction(): DatabaseTransaction {
		return new DatabaseTransaction($this->pdo);
	}

	public function Transact(callable $callback): mixed
	{
		$t = $this->NewTransaction();

		try
		{
			$result = $callback();
		}
		catch(Exception $e)
		{
			$t->Rollback();
			throw $e;
		}

		$t->Commit();

		return $result;
	}

	public function TransactQuery(DatabaseQuery $query): mixed
	{
		$t = $this->NewTransaction();

		try
		{
			$result = $query->Execute();
		}
		catch(Exception $e)
		{
			$t->Rollback();
			throw $e;
		}

		$t->Commit();

		return $result;
	}


	public function SaveChanges(): void {
		$this->pdo->commit();
		$this->pdo->beginTransaction();
	}


	public function CancelChanges(): void {
		$this->pdo->rollBack();
		$this->pdo->beginTransaction();
	}


	public function Query(): QueryBuilder {
		return new QueryBuilder($this->pdo);
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