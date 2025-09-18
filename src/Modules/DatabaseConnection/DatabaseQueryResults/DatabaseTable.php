<?php
namespace Modules\DatabaseConnection\DatabaseInfos;


use PDO;

/**
 * Represent a table in the database.
 * 
 * Instanciated by {@see DatabaseConnection::ShowTable()}
 */
class DatabaseTable
{
	private string $name;
	private PDO $pdo;
	private ?array $fields = null;


	public function __construct(string $name, PDO $pdo)
	{
		$this->name = $name;

	}


	public function getName(): string
	{
		return $this->name;
	}


	/**
	 * Fields of this table.
	 * @return void
	 */
	public function GetFields(): array {
		
	}



	public function InitFields(): void
	{


		$stmt = $this->pdo->prepare("SHOW COLUMNS FROM `:tableName`");
		$stmt->bindValue(':tableName', $this->name, PDO::PARAM_STR);
		$stmt->execute();

		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$this->fields = [];

		foreach ($data as $row)
			$this->fields[] = new DatabaseField($field['Field'], $this->pdo);

		return $fields;
	}


	public function fecth(): void
	{
		
	}
}