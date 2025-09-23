<?php
namespace Utils\Database;

use Exception;
use PDO;


/**
 * Transaction class for database transactions.
 *
 * This class provides methods to manage database transactions using savepoints.
 * It allows you to create nested transactions by maintaining a stack of active transactions.
 * 
 * The transaction begins at the instantiation, is automaically rollbacked when the object is destroyed and the transaction is still active
 * The changes are commited once every actives transaction are commited, but rollbacks affect immediately changes made in the rollbacked transaction
 * 
 * Transctions works in stacks, the latest transaction must be commited before the previous one can be commited
 * 
 */
class DatabaseTransaction
{

	private static PDO $pdo;

	private static int $currentTransactionLevel;

	/**
	 * @var DatabaseTransaction[]
	 */
	private static array $transactionStack;

	private int $transactionLevel;
	private bool $isActive = false;


	public function __construct(PDO $pdo) {
		if (self::$pdo->inTransaction())
			self::$pdo->BeginTransaction();

		$this->Init();

		self::$currentTransactionLevel++;
	}
	

	public function __destruct() {
		if ($this->isActive)
			$this->Rollback();
	}


	public function GetSavepointName(): string {
		return "transactionSave{$this->transactionLevel}";
	}


	public static function GetTransactionLevel(int $level): ?self {
		return self::$transactionStack[self::$currentTransactionLevel] ?? null;
	}


	public function IsActive(): bool {
		return $this->isActive;
	}

	
	public function Init() {
		self::$pdo->exec(
			"SAVEPOINT {$this->GetSavepointName()}"
		);

		$errorInfo = self::$pdo->errorInfo();

		$this->transactionLevel = self::$currentTransactionLevel;
		$this->isActive = true;

		if (self::GetTransactionLevel($this->transactionLevel) == null || self::GetTransactionLevel($this->transactionLevel)->isActive()) {
			unset($this);
			throw new Exception("A transaction with this level already exists. transaction creation cancelled");
		}

		self::$transactionStack[$this->transactionLevel] = $this;
	}


	public function Commit(): void {
		if (!$this->isActive)
			throw new Exception("Transaction was already commited or rolled back, or the creation failled");

		if ($this->transactionLevel !== self::$currentTransactionLevel)
			throw new Exception("Invalid transaction order, the transaction was committed but a lower transaction level is still active.");

		self::$pdo->exec(
			"RELEASE SAVEPOINT {$this->GetSavepointName()}"
		);

		self::$currentTransactionLevel--;
	}


	public function Rollback(): void {
		if (!$this->isActive)
			throw new Exception("Transaction was already commited or rolled back, or the creation failled");

		if ($this->transactionLevel !== self::$currentTransactionLevel)
			for ($i = self::$currentTransactionLevel; $i > $this->transactionLevel; $i--)
				self::$transactionStack[$i]->Rollback();

		self::$pdo->exec(
			"ROLLBACK TO SAVEPOINT {$this->GetSavepointName()}"
		);

		self::$currentTransactionLevel--;
	}
}

// SAVEPOINT identifier
// ROLLBACK [WORK] TO [SAVEPOINT] identifier
// RELEASE SAVEPOINT identifier