<?php
namespace Models;

use Exception;
use PDO;

use Traits\Singleton;

use Models\Model;

use Models\Entities\Group;
use Models\EntityLists\ListGroup;

use Core\Database\DatabaseQueryParam;
use Core\Database\ListDatabaseQueryParam;
use Core\Database\DatabaseException;


class ModelGroup extends Model
{
	use Singleton;
	
	public function NewObject(array $data): Group {
		$Group = new Group();
		$Group->id ??= $data["SECGRP_ID"];
		$Group->description = isset($data["SECGRP_DESC"]) ? convert_encoding_to_utf8($data["SECGRP_DESC"]) : "";
		
		return $Group;
	}

	
	public function NewList(array $data): ListGroup {
		$List = new ListGroup();

		foreach ($data as $Group) {
			$List->Add($this->NewObject($Group));
		}

		return $List;
	}


	#region Commandes Clients H selection queries 

	public function GetById(int $id): ?Group {
		try
		{
			$data = $this->Database->GetOne(
				"SELECT * FROM _secgrp WHERE SECUSR_ID = :id",
				new ListDatabaseQueryParam(
					new DatabaseQueryParam(":id", $id, PDO::PARAM_INT),
				)
			);

			if ($data === [])
				return null;

			return $this->NewObject($data);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}

	public function getByDescription(string $description): ?Group {
		try
		{
			return $this->NewObject(
				$this->Database->GetOne(
					"SELECT * FROM _secgrp WHERE SECGRP_DESC LIKE :descrip",
					new ListDatabaseQueryParam(
						new DatabaseQueryParam(":descrip", convert_encoding_to_iso($description), PDO::PARAM_STR),
					)
				)
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	public function GetByUserLogin(string $login): ListGroup {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					"SELECT SECGRP_ID, SECGRP_DESC FROM `_secgrp` as `g`
						LEFT JOIN `_secusrbygrp` AS `ug` ON `ug`.SECUSRBYGRP_PARENTGRPID = `g`.`SECGRP_ID`
						WHERE `ug`.`SECUSRBYGRP_LOGIN` = :user_login",
					new ListDatabaseQueryParam(
						new DatabaseQueryParam(":user_login", $login, PDO::PARAM_STR),
					)
				)
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}

	/**
	 * Get all commandes clients
	 * @param 
	 * @return ListGroup
	 */
	public function GetAll() : ListGroup {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					"SELECT * FROM _secgrp"
				)
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	public function Any(int $id): bool {
		try
		{
			$data = $this->Database->GetOne(
				"SELECT SECUSR_ID FROM _secgrp WHERE SECUSR_ID = :id",
				new ListDatabaseQueryParam(
					new DatabaseQueryParam(":id", $id, PDO::PARAM_INT),
				)
			);

			return $data !== [];
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}

	
	#endregion


	#region Modification Queries

	public function Insert(): bool {
		throw new Exception("Not implemented yet");
	}


	public function Update(): bool {
		throw new Exception("Not implemented yet");
	}
	
	
	public function Delete(): bool {
		throw new Exception("Not implemented yet");
	}
	

	public function EraseById(int $id): bool {
		throw new Exception("Not implemented yet");
	}

	#endregion
}


