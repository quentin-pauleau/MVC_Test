<?php
namespace Models;

use Exception;
use PDO;

use Traits\Singleton;

use Models\Entities\TypeConges;
use Models\EntityLists\ListTypeConges;

use Core\Database\DatabaseException;
use Core\Database\DatabaseQueryParam;
use Core\Database\ListDatabaseQueryParam;
use Core\UUID;


/**
 * This class is the model for the {@see TypeConges} database object
 * 
 * database object : {@see TypeConges}
 * list : {@see ListTypeConges}
 */
final class ModelTypeConges extends Model
{
	use Singleton;


	public function NewObject(array $data): TypeConges {
		$TypeConges = new TypeConges();

		$TypeConges->id ??= $data["F_TYPEDEMANDE_ID"];

		if (isset($data["F_TYPEDEMANDE_NOM"])) {
			$TypeConges->name = Convert_encoding_to_utf8($data["F_TYPEDEMANDE_NOM"]);
		}

		$TypeConges->order ??= $data["F_TYPEDEMANDE_ORDRE"];
		$TypeConges->active ??= $data["F_TYPEDEMANDE_ACTIF"];
		$TypeConges->dayCount ??= $data["F_TYPEDEMANDE_NBJOURDECOMPTE"];

		return $TypeConges;
	}

	
	public function NewList(array $data): ListTypeConges {
		$List = new ListTypeConges();

		foreach ($data as $TypeConges)
			$List->Add($this->NewObject($TypeConges));

		return $List;
	}

	#region Selection Queries

	public function GetById(int $id): ?TypeConges {
		try
		{
			$data = $this->Database->GetOne(
				"SELECT * FROM f_typedemande 
				WHERE F_TYPEDEMANDE_ID = :id
				",
				new ListDatabaseQueryParam(
					new DatabaseQueryParam(":id", $id, PDO::PARAM_INT)
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
	 * Get all commandes fours
	 * @param 
	 * @return ListTypeConges
	 */
	public function GetAll() : ListTypeConges {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					"SELECT * FROM f_typedemande 
					ORDER BY F_TYPEDEMANDE_ORDRE
					"
				)
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	/**
	 * Get all commandes fours
	 * @param 
	 * @return ListTypeConges
	 */
	public function GetActives() : ListTypeConges {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					"SELECT * FROM f_typedemande 
					-- WHERE F_TYPEDEMANDE_ACTIF = 1 
					ORDER BY F_TYPEDEMANDE_ORDRE
					"
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
				"SELECT F_TYPEDEMANDE_ID FROM f_typedemande 
				WHERE F_TYPEDEMANDE_ID = :id
				",
				new ListDatabaseQueryParam(
					new DatabaseQueryParam(":id", $id, PDO::PARAM_INT)
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
	
	public function Insert(TypeConges ...$ArrayTypeConges): bool {
		throw new Exception("Not implemented yet");
	}


	public function InsertList(ListTypeConges $List): bool {
		throw new Exception("Not implemented yet");
	}


	public function Update(): bool {
		throw new Exception("Not implemented yet");
	}
	
	
	public function Delete(TypeConges ...$ArrayTypeConges): bool {
		throw new Exception("Not implemented yet");
	}
	
	public function EraseById(int $id): bool {
		throw new Exception("Not implemented yet");
	}

	#endregion
}


