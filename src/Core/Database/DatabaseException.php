<?php
namespace Core\Database;

use Exception;

class DatabaseException extends Exception {
	public int $QueryType;
	public function __construct($message, int $QueryType, $code = 0, $previous = null) {
		parent::__construct($message, $code, $previous);
		$this->QueryType = $QueryType;
	}

	public function getQueryType(): int {
		return $this->QueryType;
	}
}