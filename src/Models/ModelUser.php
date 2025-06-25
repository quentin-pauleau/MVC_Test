<?php
namespace Models;

use Exception;
use PDO;

use Traits\Singleton;

use Models\Model;

use Core\Database\ListDatabaseQueryParam;
use Core\Database\DatabaseQueryParam;
use Core\Database\DatabaseException;

use Models\Entities\User;
use Models\EntityLists\ListUser;


class ModelUser extends Model
{
	use Singleton;

	public function NewObject(array $data): User {
		$User = new User();
		$User->id ??= $data["SECUSR_ID"];
		$User->login = isset($data["SECUSR_LOGIN"]) ? convert_encoding_to_utf8($data["SECUSR_LOGIN"]) : "";
		$User->fname = isset($data["SECUSR_FNAME"]) ? convert_encoding_to_utf8($data["SECUSR_FNAME"]) : "";
		$User->lname = isset($data["SECUSR_LNAME"]) ? convert_encoding_to_utf8($data["SECUSR_FNAME"]) : "";
		$User->pass = $data["SECUSR_PWD"] ?? "";
		
		return $User;
	}

	public function NewList(array $data): ListUser {
		$List = new ListUser();

		foreach ($data as $User) {
			$List->Add(self::NewObject($User));
		}

		return $List;
	}


	#region Commandes Clients H selection queries 

	/**
	 * Summary of GetById
	 * @param int $id
	 * @return ?User
	 */
	public function GetById(int $id): ?User {
		try
		{
			$data = $this->Database->GetOne(
				"SELECT * FROM user_ged WHERE SECUSR_ID = :id",
				new ListDatabaseQueryParam(
					new DatabaseQueryParam(":id", $id, PDO::PARAM_INT),
				)
			);

			if ($data === [])
				return null;

			return $this->NewObject($data);
		}
		catch (DatabaseException $e) {
			throw $e;
		}
	}

	public function GetByLogin(string $login): ?User {
		try
		{
			return $this->NewObject(
				$this->Database->GetOne(
					"SELECT * FROM user_ged WHERE SECUSR_LOGIN LIKE :usr_login",
					new ListDatabaseQueryParam(
						new DatabaseQueryParam(":usr_login", convert_encoding_to_iso($login), PDO::PARAM_STR),
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
	 * @return ListUser
	 */
	public function GetAll() : ListUser {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					"SELECT SECUSR_ID, SECUSR_LNAME, SECUSR_FNAME FROM user_ged"
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
				"SELECT SECUSR_ID FROM user_ged WHERE SECUSR_ID = :id",
				new ListDatabaseQueryParam(
					new DatabaseQueryParam(":id", $id, PDO::PARAM_INT),
				)
			);

			return $data !== [];
		}
		catch (DatabaseException $e) {
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


