<?php

namespace Modules\DatabaseConnection;

use PDO;

class DatabaseQuery
{
	private PDO $pdo;
	private string $query;

	public function __construct(PDO $pdo, string $query) {
		$this->pdo = $pdo;
	}

	public function Execute(): mixed {
		return $this->pdo->exec($this->query);
	}
}