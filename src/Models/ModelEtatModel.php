<?php
namespace Models;

use Exception;
use PDO;

use Traits\Singleton;

use Models\Model;

use Models\Entities\EtatModel;
use Models\EntityLists\ListEtatModel;

use Core\Database\ListDatabaseQueryParam;
use Core\Database\DatabaseQueryParam;
use Core\Database\DatabaseException;

class ModelEtatModel extends Model
{
	use Singleton;

	public function NewObject(array $data): EtatModel {
		$EtatModel = new EtatModel();

		$EtatModel->id ??= $data["ETATMODELE_ID"];
		$EtatModel->des = isset($data["ETATMODELE_DES"]) ? convert_encoding_to_utf8($data["ETATMODELE_DES"]) : "";
		$EtatModel->is_cli ??= $data["ETATMODELE_CLI"];
		$EtatModel->is_four ??= $data["ETATMODELE_FOUR"];
		$EtatModel->lignes ??= $data["ETATMODELE_LIGNES"];
		$EtatModel->dem ??= $data["ETATMODELE_DEM"];

		return $EtatModel;
	}

	
	public function NewList(array $data): ListEtatModel {
		$List = new ListEtatModel();

		foreach ($data as $EtatModel) {
			$List->Add(self::NewObject($EtatModel));
		}

		return $List;
	}


	#region Urgences selection queries 

	public function GetById(int $id): ?EtatModel {
		try
		{
			$data = $this->Database->GetOne(
				"SELECT * FROM etatmodele WHERE ETATMODELE_ID = :id", 
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
	 * Get all urgences
	 * @param 
	 * @return ListEtatModel
	 */
	public function GetAll() : ListEtatModel {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					"SELECT * FROM etatmodele"
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
				"SELECT ETATMODELE_ID FROM etatmodele WHERE ETATMODELE_ID = :id", 
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


	/**
	 * Get all urgences for client
	 * @param 
	 * @return ListEtatModel
	 */
	public function GetClient() : ListEtatModel {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					"SELECT * FROM etatmodele WHERE ETATMODELE_CLI = 1 
					ORDER BY ETATMODELE_DES"
				)
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	/**
	 * Get all urgences for fournisseurs
	 * @param 
	 * @return ListEtatModel
	 */
	public function GetFournisseur() : ListEtatModel {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					"SELECT * FROM etatmodele WHERE ETATMODELE_FOUR = 1 
					ORDER BY ETATMODELE_DES"
				)
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}



	/**
	 * Get all urgences for fournisseurs
	 * @param 
	 * @return ListEtatModel
	 */
	public function GetDemandeDiverse() : ListEtatModel {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					"SELECT * FROM etatmodele WHERE ETATMODELE_DEM = 1 
					ORDER BY ETATMODELE_DES"
				)
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	/**
	 * Get all urgences for fournisseurs
	 * @param 
	 * @return ListEtatModel
	 */
	public function GetLignes() : ListEtatModel {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					"SELECT * FROM etatmodele WHERE ETATMODELE_LIGNES = 1 
					ORDER BY ETATMODELE_DES"
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


