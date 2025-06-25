<?php
namespace Models;

use Exception;
use PDO;

use Traits\Singleton;

use Models\Model;

use Models\Entities\TypeLivraison;
use Models\EntityLists\ListTypeLivraison;

use Core\Database\ListDatabaseQueryParam;
use Core\Database\DatabaseQueryParam;
use Core\Database\DatabaseException;

class ModelTypeLivraison extends Model
{
	use Singleton;

	public function NewObject(array $data): TypeLivraison {
		$TypeLivraison = new TypeLivraison();
		$TypeLivraison->id ??= $data["TYPELIVRAISON_ID"];
		$TypeLivraison->des = isset($data["TYPELIVRAISON_DES"]) ? convert_encoding_to_utf8($data["TYPELIVRAISON_DES"]) : "";
		
		return $TypeLivraison;
	}

	
	public function NewList(array $data): ListTypeLivraison {
		$List = new ListTypeLivraison();

		foreach ($data as $User) {
			$List->Add(self::NewObject($User));
		}

		return $List;
	}


	#region Type Livraison selection queries 

	public function GetById(int $id): ?TypeLivraison {
		try
		{
			return $this->NewObject(
				$this->Database->GetOne(
					"SELECT * FROM typelivraison WHERE TYPELIVRAISON_ID = :id",
					new ListDatabaseQueryParam(
						new DatabaseQueryParam(":id", $id, PDO::PARAM_INT),
					)
				)
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	public function Any(int $id): bool {
		$data = $this->Database->GetOne(
			"SELECT * FROM typelivraison WHERE TYPELIVRAISON_ID = :id",
			new ListDatabaseQueryParam(
				new DatabaseQueryParam(":id", $id, PDO::PARAM_INT),
			)
		);

		return $data !== [];
	}


	/**
	 * Get all types livraison
	 * @param 
	 * @return ListTypeLivraison
	 */
	public function GetAll() : ListTypeLivraison {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					"SELECT * FROM typelivraison"
				)
			);
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


