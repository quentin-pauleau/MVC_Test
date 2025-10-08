<?php
namespace Core\Database;

use PDO;
use PDOStatement;
use Exception;


class DatabaseQueryParam
{
	public string $bind;
	public $value;
	public ?int $PDO_ParamType = null;
	public function __construct(string $bind, $value = null, ?int $PDO_ParamType = null) {
		$this->bind = $bind;
		$this->value = $value;

		if ($PDO_ParamType != null) {
			$this->PDO_ParamType = $PDO_ParamType;
			return;
		}
	}

	public function __tostring(): string {
		return "$this->bind => $this->value";
	}


	public static function DefinePDOParamType($var): int {
		if (is_int($var)) {
			return PDO::PARAM_INT;
		} elseif (is_bool($var)) {
			return PDO::PARAM_BOOL;
		} elseif (is_string($var)) {
			return PDO::PARAM_STR;
		} elseif ($var === null) {
			return PDO::PARAM_NULL;
		} else {
			throw new Exception("Type de variable non supporté");
		}
	}


	public function BindAsParam(PDOStatement $Statement): bool {
		if ($this->PDO_ParamType === null) {
			return $Statement->bindParam($this->bind, $this->value);
		} else {
			return $Statement->bindParam($this->bind, $this->value, $this->PDO_ParamType);
		}
	}

	public function BindAsValue(PDOStatement $Statement): bool {
		if ($this->PDO_ParamType === null) {
			return $Statement->bindParam($this->bind, $this->value);
		} else {
			return $Statement->bindParam($this->bind, $this->value, $this->PDO_ParamType);
		}
	}

	public function isBindPossible(PDOStatement $Statement): bool {
		if ($Statement->queryString === "") {
			throw new DatabaseException(
				"La requête n'est pas dénfinie",
				DatabaseQueryType::UNKNOWN,
				DatabaseErrorCode::BINDING_FAILED
			);
		}

		if (!str_starts_with2($this->bind, ":")) {
			$this->bind = ":$this->bind";
		}

		if (!str_contains2($Statement->queryString, $this->bind)) {
			throw new DatabaseException(
				"Le paramètre '$this->bind' ne peut pas être attaché à la requête",
				DatabaseQueryType::UNKNOWN,
				DatabaseErrorCode::BINDING_FAILED
			);
		}


		return true;
	}

	public static function isBindPossibleString(PDOStatement $Statement, string $bind): bool {
		if ($Statement->queryString === null)
			throw new Exception("La requête n'est pas dénfinie", 1);

		if (!str_starts_with2($bind, ":"))
			$bind = ":$bind";
		
		return str_contains2($Statement->queryString, $bind);
	}
}