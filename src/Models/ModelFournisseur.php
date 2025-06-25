<?php
namespace Models;

use Exception;
use PDO;

use Traits\Singleton;

use Models\EntityLists\ListFournisseur;
use Models\Entities\Fournisseur;

use Core\Database\DatabaseException;
use Core\Database\DatabaseQueryParam;
use Core\Database\ListDatabaseQueryParam;


class ModelFournisseur extends Model
{
	use Singleton;

	public function NewObject(array $data): Fournisseur {
		$Fournisseur = new Fournisseur();
		$Fournisseur->id ??= $data["COMPTAFOURNISSEUR_ID"];
		$Fournisseur->name = isset($data["COMPTAFOURNISSEUR_NOM"]) ? convert_encoding_to_utf8($data["COMPTAFOURNISSEUR_NOM"]) : "";

		return $Fournisseur;
	}

	
	public function NewList(array $data): ListFournisseur {
		$List = new ListFournisseur();

		foreach ($data as $Fournisseur)
			$List->Add($this->NewObject($Fournisseur));

		return $List;
	}



	#region Commandes Clients H selection queries 

	/**
	 * Summary of GetById
	 * @param int $id
	 * @return ?Fournisseur
	 */
	public function GetById(int $id): ?Fournisseur {
		try
		{
			$data = $this->Database->GetOne(
				"SELECT COMPTAFOURNISSEUR_ID, COMPTAFOURNISSEUR_NOM 
				FROM comptafournisseur WHERE COMPTAFOURNISSEUR_ID = :id",
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


	/**
	 * Get all commandes clients
	 * @param 
	 * @return ListFournisseur
	 */
	public function GetAll() : ListFournisseur {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					"SELECT COMPTAFOURNISSEUR_ID, COMPTAFOURNISSEUR_NOM 
					FROM comptafournisseur
					ORDER BY COMPTAFOURNISSEUR_NOM"
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
				"SELECT COMPTAFOURNISSEUR_ID
				FROM comptafournisseur WHERE COMPTAFOURNISSEUR_ID = :id",
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


