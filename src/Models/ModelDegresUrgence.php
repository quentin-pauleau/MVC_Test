<?php
namespace Models;

use Exception;
use PDO;

use Traits\Singleton;

use Models\Model;

use Models\Entities\DegresUrgence;
use Models\EntityLists\ListDegresUrgence;

use Utils\Database\DatabaseException;
use Utils\Database\DatabaseQueryParam;
use Utils\Database\ListDatabaseQueryParam;

class ModelDegresUrgence extends Model
{
	use Singleton;

	public function NewObject(array $data): DegresUrgence {
		$Urgence = new DegresUrgence();
		$Urgence->id ??= $data["DEGRESURGENCE_ID"];
		$Urgence->des = isset($data["DEGRESURGENCE_DES"]) ? convert_encoding_to_utf8($data["DEGRESURGENCE_DES"]) : "";
		$Urgence->is_cli ??= $data["DEGRESURGENCE_CLI"];
		$Urgence->is_four ??= $data["DEGRESURGENCE_FOUR"];
		
		return $Urgence;
	}

	
	public function NewList(array $data): ListDegresUrgence {
		$List = new ListDegresUrgence();

		foreach ($data as $DegresUrgence) {
			$List->Add($this->NewObject($DegresUrgence));
		}

		return $List;
	}

	#region Urgences selection queries 


	public function GetById(int $id): ?DegresUrgence {
		
		$data = $this->Database->GetOne(
			"SELECT * FROM degresurgence WHERE DEGRESURGENCE_ID = :id",
			new ListDatabaseQueryParam(
				new DatabaseQueryParam(":id", $id, PDO::PARAM_INT)
			)
		);

		if ($data === [])
			return null;

		return $this->NewObject($data);
	}


	/**
	 * Get all urgences
	 * @param 
	 * @return ListDegresUrgence
	 */
	public function GetAll() : ListDegresUrgence {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					"SELECT * FROM degresurgence"
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
			"SELECT DEGRESURGENCE_ID FROM degresurgence WHERE DEGRESURGENCE_ID = :id",
			new ListDatabaseQueryParam(
				new DatabaseQueryParam(":id", $id, PDO::PARAM_INT)
			)
		);

		return $data !== [];
	}


	/**
	 * Get all urgences for client
	 * @param 
	 * @return ListDegresUrgence
	 */
	public function GetAllClient() : ListDegresUrgence {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					"SELECT * FROM degresurgence WHERE DEGRESURGENCE_CLI = 1"
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
	 * @return ListDegresUrgence
	 */
	public function GetAllFournisseur() : ListDegresUrgence {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					"SELECT * FROM degresurgence WHERE DEGRESURGENCE_FOUR = 1"
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


