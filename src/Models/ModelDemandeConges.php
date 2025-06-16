<?php
namespace Models;

use DateTime;
use Enums\ComparaisonOpperators;
use Enums\DemandeCongesStates;
use Exception;
use PDO;

use Traits\Singleton;

use Models\Entities\DemandeConges;
use Models\EntityLists\ListDemandeConges;

use Utils\Database\Database;
use Utils\Database\DatabaseException;
use Utils\Database\DatabaseQueryParam;
use Utils\Database\ListDatabaseQueryParam;
use Utils\UUID;

/**
 * This class is the model for the {@see DemandeConges} database object
 * 
 * database object : {@see DemandeConges}
 * list : {@see ListDemandeConges}
 */
class ModelDemandeConges extends Model
{
	use Singleton;

	/**
	 * @param array $data
	 * @return DemandeConges
	 */
	public function NewObject(array $data): DemandeConges
	{
		$DemandeConges = new DemandeConges;
		
		$DemandeConges->id ??= $data["F_DEMANDECP_ID"];

		$DemandeConges->id_demandeur ??= $data["F_DEMANDECP_SECURSID"];
		$DemandeConges->id_type_conges ??= $data["F_DEMANDECP_IDTYPE"];

		if (isset($data["F_DEMANDECP_COMMENTAIRE"]))
			$DemandeConges->comment = Convert_encoding_to_utf8($data["F_DEMANDECP_COMMENTAIRE"]);

		if (isset($data["F_DEMANDECP_DATEDEBUT"]))
			$DemandeConges->StartDate = DateTime::createFromFormat(Database::DATE_FORMAT, $data["F_DEMANDECP_DATEDEBUT"]) ?: null;

		if (isset($data["F_DEMANDECP_DEBUTMATIN"]))
			$DemandeConges->StartMorning = $data["F_DEMANDECP_DEBUTMATIN"] == 1 ? true : false;

		if (isset($data["F_DEMANDECP_DATEFIN"]))
			$DemandeConges->EndDate = DateTime::createFromFormat(Database::DATE_FORMAT, $data["F_DEMANDECP_DATEFIN"]) ?: null;
		
		if (isset($data["F_DEMANDECP_FINAPREM"]))
			$DemandeConges->EndAfternoon = $data["F_DEMANDECP_FINAPREM"] == 1 ? true : false;

		if (isset($data["F_DEMANDECP_ETATAFF"]))
			$DemandeConges->state ??= Convert_encoding_to_utf8($data["F_DEMANDECP_ETATAFF"]);

		//* 1st director action
		if (isset($data["F_DEMANDECP_VALID"]))
			$DemandeConges->is_validated1 = $data["F_DEMANDECP_VALID"] == 1 ? true : false;

		if (isset($data["F_DEMANDECP_REFUS"]))
			$DemandeConges->is_denied1 = $data["F_DEMANDECP_REFUS"] == 1 ? true : false;

		if (isset($data["F_DEMANDECP_ACCEPT"]))
			$DemandeConges->is_accepted1 = $data["F_DEMANDECP_ACCEPT"] == 1 ? true : false;

		//* 1nd director action
		if (isset($data["F_DEMANDECP_VALID2"]))
			$DemandeConges->is_validated2 = $data["F_DEMANDECP_VALID2"] == 1 ? true : false;

		if (isset($data["F_DEMANDECP_REFUS2"]))
			$DemandeConges->is_denied2 = $data["F_DEMANDECP_REFUS2"] == 1 ? true : false;

		if (isset($data["F_DEMANDECP_ACCEPT2"]))
			$DemandeConges->is_accepted2 = $data["F_DEMANDECP_ACCEPT2"] == 1 ? true : false;

		//* demandeur action
		if (isset($data["F_DEMANDECP_ANNUL"]))
			$DemandeConges->is_canceled = $data["F_DEMANDECP_ANNUL"] == 1 ? true : false;

		//* uuid
		if (isset($data["F_DEMANDECP_UUID"])) {
			$DemandeConges->UUID ??= UUID::FromString($data["F_DEMANDECP_UUID"]);
		}
		
		return $DemandeConges;
	}


	/**
	 * @param array $data
	 * @return ListDemandeConges
	 */
	public function NewList(array $data): ListDemandeConges
	{
		$List = new ListDemandeConges();

		foreach ($data as $DemandeConges)
		{
			$List->Add($this->NewObject($DemandeConges));
		}

		return $List;
	}

	#region Selection Queries

	/**
	 * @param int $id
	 * @return Entities\DemandeConges
	 */
	public function GetById(int $id): ?DemandeConges
	{
		try
		{
			$data = $this->Database->GetOne(
				"SELECT * FROM f_demandecp WHERE F_DEMANDECP_ID = :id",
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
	 * @return ListDemandeConges
	 */
	public function GetAll(): ListDemandeConges
	{
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					"SELECT * FROM f_demandecp"
				)
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}
	
	
	/**
	 * Get all 'demande de conges' with the given 'demandeur'
	 * @param int $id_demandeur
	 * @return ListDemandeConges
	 */
	public function GetByDemandeurId(int $id_demandeur) : ListDemandeConges {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					"SELECT * FROM f_demandecp WHERE F_DEMANDECP_SECURSID = :id_demandeur",
					new ListDatabaseQueryParam(
						new DatabaseQueryParam(":id_demandeur", $id_demandeur, PDO::PARAM_INT)
					)
				)
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}

	
	public function Any(int $id): bool
	{
		try
		{
			$data = $this->Database->GetOne(
				"SELECT F_DEMANDECP_ID FROM f_demandecp WHERE F_DEMANDECP_ID = :id",
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

	
	/**
	 * @param int $id_demandeur
	 * @param int[] $type_conges_ids
	 * @param int[] $state_ids
	 * @return ListDemandeConges
	 */
	public function GetLike(
		DemandeConges $DemandeConges, 
		?array $type_conges_ids = null, 
		?array $state_ids = null,
		?int $start_date_opperator = null,
		?int $end_date_opperator = null
	) : ListDemandeConges {
		try
		{
			$query = 'SELECT * FROM f_demandecp WHERE ';
			$ListDatabaseQueryParam = new ListDatabaseQueryParam;


			if ($state_ids !== null && $state_ids != []) {
				$query .= '(';
				foreach ($state_ids as $key => $state_id) {
					switch ($state_id) {
						case DemandeCongesStates::NOT_TREATED_1:
							$query .= "(F_DEMANDECP_ETATAFF LIKE :id_state_$key AND (F_DEMANDECP_ACCEPT = 0 AND F_DEMANDECP_REFUS = 0)) OR ";
							$ListDatabaseQueryParam->Add(
								new DatabaseQueryParam(":id_state_$key", DemandeCongesStates::GetDescription(DemandeCongesStates::PENDING), PDO::PARAM_STR)
							);
							break;

						case DemandeCongesStates::NOT_TREATED_2:
							$query .= "(F_DEMANDECP_ETATAFF LIKE :id_state_$key AND (F_DEMANDECP_ACCEPT2 = 0 AND F_DEMANDECP_REFUS2 = 0)) OR ";
							$ListDatabaseQueryParam->Add(
								new DatabaseQueryParam(":id_state_$key", DemandeCongesStates::GetDescription(DemandeCongesStates::PENDING), PDO::PARAM_STR)
							);
							break;

						case DemandeCongesStates::TREATED_1:
							$query .= "(F_DEMANDECP_ETATAFF LIKE :id_state_$key AND (F_DEMANDECP_ACCEPT = 1 OR F_DEMANDECP_REFUS = 1)) OR ";
							$ListDatabaseQueryParam->Add(
								new DatabaseQueryParam(":id_state_$key", DemandeCongesStates::GetDescription(DemandeCongesStates::PENDING), PDO::PARAM_STR)
							);
							break;
						
						case DemandeCongesStates::TREATED_2:
							$query .= "(F_DEMANDECP_ETATAFF LIKE :id_state_$key AND (F_DEMANDECP_ACCEPT2 = 1 OR F_DEMANDECP_REFUS2 = 1)) OR ";
							$ListDatabaseQueryParam->Add(
								new DatabaseQueryParam(":id_state_$key", DemandeCongesStates::GetDescription(DemandeCongesStates::PENDING), PDO::PARAM_STR)
							);
							break;

						default:
							$query .= "F_DEMANDECP_ETATAFF LIKE :id_state_$key OR ";
							$ListDatabaseQueryParam->Add(
								new DatabaseQueryParam(":id_state_$key", DemandeCongesStates::GetDescription($state_id), PDO::PARAM_STR)
							);
							break;
					}
				}
	
				if (str_ends_with2($query, 'OR ')) {
					$query = substr($query, 0, -3);
				}
	
				$query .= ') AND ';
			}


			if ($type_conges_ids === null || $type_conges_ids === []) {
				if ($DemandeConges->id_type_conges != null) {
					$query .= 'F_DEMANDECP_IDTYPE = :id_type_conges AND ';
					$ListDatabaseQueryParam->Add(
						new DatabaseQueryParam(':id_type_conges', $DemandeConges->id_type_conges, PDO::PARAM_INT)
					);
				}
			} else {
				$query .= '(';
				foreach ($type_conges_ids as $key => $type_conges_id) {
					$query .= "F_DEMANDECP_IDTYPE = :id_type_conges_$key OR ";
					$ListDatabaseQueryParam->Add(
						new DatabaseQueryParam(":id_type_conges_$key", $type_conges_id, PDO::PARAM_INT)
					);
				}
	
				if (str_ends_with2($query, 'OR ')) {
					$query = substr($query, 0, -3);
				}
	
				$query .= ') AND ';
			}


			if ($DemandeConges->id_demandeur != null) {
				$query .= 'F_DEMANDECP_SECURSID = :id_demandeur AND ';
				$ListDatabaseQueryParam->Add(
					new DatabaseQueryParam(':id_demandeur', $DemandeConges->id_demandeur, PDO::PARAM_INT)
				);
			}


			if ($DemandeConges->StartDate != null && $start_date_opperator != null) {
				switch ($start_date_opperator) {
					case ComparaisonOpperators::EQUALS: // equals
						$query .= 'F_DEMANDECP_DATEDEBUT = :start_date AND ';
						break;
					
					case ComparaisonOpperators::DIFFERENT: // different than
						$query .= 'F_DEMANDECP_DATEDEBUT <> :start_date AND ';
						break;
					
					
					case ComparaisonOpperators::GREATER_THAN: // greater than
						$query .= 'F_DEMANDECP_DATEDEBUT > :start_date AND ';
						break;

					case ComparaisonOpperators::GREATER_THAN_EQUALS: // greater than or equals
						$query .= 'F_DEMANDECP_DATEDEBUT >= :start_date AND ';
						break;

					
					case ComparaisonOpperators::LESS_THAN: // less than
						$query .= 'F_DEMANDECP_DATEDEBUT < :start_date AND ';
						break;

					case ComparaisonOpperators::LESS_THAN_EQUALS: // less than or equals
						$query .= 'F_DEMANDECP_DATEDEBUT <= :start_date AND ';
						break;
					
					default:
						throw new Exception("invalid opperator");
				}

				$date = $DemandeConges->StartDate->format(Database::DATE_FORMAT);

				$ListDatabaseQueryParam->Add(
					new DatabaseQueryParam(':start_date', $date, PDO::PARAM_STR)
				);
			}


			if ($DemandeConges->EndDate != null && $end_date_opperator != null) {
				switch ($end_date_opperator) {
					case ComparaisonOpperators::EQUALS: // equals
						$query .= 'F_DEMANDECP_DATEFIN = :end_date AND ';
						break;
					
					case ComparaisonOpperators::DIFFERENT: // different
						$query .= 'F_DEMANDECP_DATEFIN <> :end_date AND ';
						break;
					
					
					case ComparaisonOpperators::GREATER_THAN: // greater than
						$query .= 'F_DEMANDECP_DATEFIN > :end_date AND ';
						break;

					case ComparaisonOpperators::GREATER_THAN_EQUALS: // greater than equals
						$query .= 'F_DEMANDECP_DATEFIN >= :end_date AND ';
						break;

					
					case ComparaisonOpperators::LESS_THAN: // less than
						$query .= 'F_DEMANDECP_DATEFIN < :end_date AND ';
						break;

					case ComparaisonOpperators::LESS_THAN_EQUALS: // less than equals
						$query .= 'F_DEMANDECP_DATEFIN <= :end_date AND ';
						break;
					
					
					default:
						throw new Exception("invalid opperator");
				}

				$date = $DemandeConges->EndDate->format(Database::DATE_FORMAT);

				$ListDatabaseQueryParam->Add(
					new DatabaseQueryParam(':end_date', $date, PDO::PARAM_STR)
				);
			}


			if (str_ends_with2($query, 'WHERE ')) {
				$query = substr($query, 0, -6);
			} elseif (str_ends_with2($query, 'AND ')) {
				$query = substr($query, 0, -4);
			}

			
			return $this->NewList(
				$this->Database->GetList(
					$query,
					$ListDatabaseQueryParam
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
	public function Insert(DemandeConges ...$ArrayDemandeConges): bool
	{
		if ($ArrayDemandeConges === [])
			return true;

		try
		{
			$nb_inserts = 0;

			foreach ($ArrayDemandeConges as $DemandeConges)
			{
				$DemandeConges->id = $this->Database->InsertOne(
					"INSERT INTO f_demandecp 
					(F_DEMANDECP_SECURSID, F_DEMANDECP_IDTYPE, 
					F_DEMANDECP_DATEDEBUT, F_DEMANDECP_DEBUTMATIN, F_DEMANDECP_DATEFIN, F_DEMANDECP_FINAPREM, 
					F_DEMANDECP_COMMENTAIRE)
					VALUES (:id_demandeur, :id_type_conges, 
					:date_debut, :debut_matin, :date_fin, :fin_aprem, 
					:commentaire)",
	
					new ListDatabaseQueryParam(
						new DatabaseQueryParam(":id_demandeur", $DemandeConges->id_demandeur, PDO::PARAM_INT),
						new DatabaseQueryParam(":id_type_conges", $DemandeConges->id_type_conges, PDO::PARAM_INT),
						new DatabaseQueryParam(":date_debut", $DemandeConges->StartDate->format("Y-m-d"), PDO::PARAM_STR),
						new DatabaseQueryParam(":debut_matin", $DemandeConges->StartMorning, PDO::PARAM_BOOL),
						new DatabaseQueryParam(":date_fin", $DemandeConges->EndDate->format("Y-m-d"), PDO::PARAM_STR),
						new DatabaseQueryParam(":fin_aprem", $DemandeConges->EndAfternoon, PDO::PARAM_BOOL),
						new DatabaseQueryParam(":commentaire", $DemandeConges->comment, PDO::PARAM_STR),
					)
				);

				$nb_inserts++;
			}

			return $nb_inserts === count($ArrayDemandeConges);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	public function InsertList(ListDemandeConges $List): bool
	{
		if ($List->IsEmpty())
		{
			return true;
		}

		try
		{
			$query = "INSERT INTO f_demandecp 
				(F_DEMANDECP_SECURSID, F_DEMANDECP_IDTYPE, 
				F_DEMANDECP_DATEDEBUT, F_DEMANDECP_DEBUTMATIN,F_DEMANDECP_DATEFIN, F_DEMANDECP_FINAPREM, 
				F_DEMANDECP_COMMENTAIRE, F_DEMANDECP_UUID)
				VALUES ";

			$QueryParams = new ListDatabaseQueryParam();

			foreach ($List as $key => $DemandeConges)
			{
				$query .= "(:id_demandeur_$key, :id_type_conges_$key, 
				:date_debut_$key, :debut_matin_$key, :date_fin_$key, 
				:demande_$key, :commentaire_$key, :UUID_$key), ";

				$QueryParams->Add(
					new DatabaseQueryParam(":id_demandeur_$key", $DemandeConges->id_demandeur, PDO::PARAM_INT),
					new DatabaseQueryParam(":id_type_conges_$key", $DemandeConges->id_type_conges, PDO::PARAM_INT),
					new DatabaseQueryParam(":date_debut_$key", $DemandeConges->StartDate->format("Y-m-d")),
					new DatabaseQueryParam(":debut_matin_$key", $DemandeConges->StartMorning, PDO::PARAM_BOOL),
					new DatabaseQueryParam(":demande_$key", $DemandeConges->EndDate->format("Y-m-d")),
					new DatabaseQueryParam(":debut_matin_$key", $DemandeConges->EndAfternoon, PDO::PARAM_BOOL),
					new DatabaseQueryParam(":commentaire_$key", $DemandeConges->comment, PDO::PARAM_STR),
					new DatabaseQueryParam(":UUID_$key", $DemandeConges->UUID, PDO::PARAM_STR),
				);
			}
			
			if (str_ends_with2($query, ", "))
			{
				$query = rtrim($query, ", ");
			}

			$nb_inserts = $this->Database->InsertList(
				$query,
				$QueryParams
			);

			return $nb_inserts === count($List);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	/**
	 * Summary of Update
	 * @param \Models\Entities\DemandeConges[] $DemandesConges
	 * @return bool
	 */
	public function Update(DemandeConges ...$DemandesConges): bool{
		if ($DemandesConges === []) {
			return true;
		}
		
		try
		{
			$query = '';
			$QueryParams = new ListDatabaseQueryParam;

			foreach ($DemandesConges as $key => $DemandeConges) {
				$query .= "UPDATE f_demandecp SET 
				F_DEMANDECP_ANNUL = :is_canceled_$key, 

				F_DEMANDECP_ACCEPT = :is_accepted1_$key, 
				F_DEMANDECP_REFUS = :is_denied1_$key, 

				F_DEMANDECP_ACCEPT2 = :is_accepted2_$key, 
				F_DEMANDECP_REFUS2 = :is_denied2_$key 

				WHERE F_DEMANDECP_ID = :id_$key; ";
				
				$QueryParams->Add(
					new DatabaseQueryParam(":id_$key", $DemandeConges->id, PDO::PARAM_INT), 

					new DatabaseQueryParam(":is_canceled_$key", $DemandeConges->is_canceled ? 1 : 0, PDO::PARAM_INT), 

					new DatabaseQueryParam(":is_accepted1_$key", $DemandeConges->is_accepted1 ? 1 : 0, PDO::PARAM_INT), 
					new DatabaseQueryParam(":is_denied1_$key", $DemandeConges->is_denied1 ? 1 : 0, PDO::PARAM_INT), 

					new DatabaseQueryParam(":is_accepted2_$key", $DemandeConges->is_accepted2 ? 1 : 0, PDO::PARAM_INT), 
					new DatabaseQueryParam(":is_denied2_$key", $DemandeConges->is_denied2 ? 1 : 0, PDO::PARAM_INT)
				);
			}

			// dd($query, $QueryParams);

			$nb_updates = $this->Database->Update(
				$query,
				$QueryParams
			);

			return $nb_updates === count($DemandesConges);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	/**
	 * @param int[] $ids
	 * @return bool
	 */
	public function SetCanceled(int ...$ids): bool {
		if ($ids === []) {
			return true;
		}
		
		try
		{
			$query = '';
			$QueryParams = new ListDatabaseQueryParam;

			foreach ($ids as $key => $id) {
				$query .= "UPDATE f_demandecp SET 
				F_DEMANDECP_ANNUL = 1 
				WHERE F_DEMANDECP_ID = :id_$key ;";
				
				$QueryParams->Add(
					new DatabaseQueryParam(":id_$key", $id, PDO::PARAM_INT)
				);
			}

			$nb_updates = $this->Database->Update(
				$query,
				$QueryParams
			);

			return $nb_updates === count($ids);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	/**
	 * @param int[] $ids
	 * @return bool
	 */
	public function SetAccept1(int ...$ids): bool {
		if ($ids === []) {
			return true;
		}
		
		try
		{
			$query = '';
			$QueryParams = new ListDatabaseQueryParam;

			foreach ($ids as $key => $id) {
				$query .= "UPDATE f_demandecp SET 
				F_DEMANDECP_ACCEPT = 1,
				F_DEMANDECP_REFUS = 0 
				WHERE F_DEMANDECP_ID = :id_$key; ";
				
				$QueryParams->Add(
					new DatabaseQueryParam(":id_$key", $id, PDO::PARAM_INT)
				);
			}

			$nb_updates = $this->Database->Update(
				$query,
				$QueryParams
			);

			return $nb_updates === count($ids);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	/**
	 * @param int[] $ids
	 * @return bool
	 */
	public function SetDeny1(int ...$ids): bool {
		if ($ids === []) {
			return true;
		}
		
		try
		{
			$query = '';
			$QueryParams = new ListDatabaseQueryParam;

			foreach ($ids as $key => $id) {
				$query .= "UPDATE f_demandecp SET 
				F_DEMANDECP_ACCEPT = 0,
				F_DEMANDECP_REFUS = 1 
				WHERE F_DEMANDECP_ID = :id_$key; ";
				
				$QueryParams->Add(
					new DatabaseQueryParam(":id_$key", $id, PDO::PARAM_INT)
				);
			}

			$nb_updates = $this->Database->Update(
				$query,
				$QueryParams
			);

			return $nb_updates === count($ids);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	/**
	 * @param int[] $ids
	 * @return bool
	 */
	public function SetAccept2(int ...$ids): bool {
		if ($ids === []) {
			return true;
		}
		
		try
		{
			$query = '';
			$QueryParams = new ListDatabaseQueryParam;

			foreach ($ids as $key => $id) {
				$query .= "UPDATE f_demandecp SET 
				F_DEMANDECP_ACCEPT2 = 1,
				F_DEMANDECP_REFUS2 = 0 
				WHERE F_DEMANDECP_ID = :id_$key; ";
				
				$QueryParams->Add(
					new DatabaseQueryParam(":id_$key", $id, PDO::PARAM_INT)
				);
			}

			$nb_updates = $this->Database->Update(
				$query,
				$QueryParams
			);

			return $nb_updates === count($ids);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	/**
	 * @param int[] $ids
	 * @return bool
	 */
	public function SetDeny2(int ...$ids): bool {
		if ($ids === []) {
			return true;
		}
		
		try
		{
			$query = '';
			$QueryParams = new ListDatabaseQueryParam;

			foreach ($ids as $key => $id) {
				$query .= "UPDATE f_demandecp SET 
				F_DEMANDECP_ACCEPT2 = 0,
				F_DEMANDECP_REFUS2 = 1 
				WHERE F_DEMANDECP_ID = :id_$key; ";
				
				$QueryParams->Add(
					
					new DatabaseQueryParam(":id_$key", $id, PDO::PARAM_INT)
				);
			}

			$nb_updates = $this->Database->Update(
				$query,
				$QueryParams
			);

			return $nb_updates === count($ids);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}



	public function Delete(DemandeConges ...$ArrayDemandeConges): bool
	{
		if ($ArrayDemandeConges === [])
		{
			return true;
		}


		try
		{
			$query = "DELETE f_demandecp WHERE ";

			$QueryParams = new ListDatabaseQueryParam();

			foreach ($ArrayDemandeConges as $key => $DemandeConges)
			{
				$query .= "F_DEMANDECP_ID = :id_$key OR ";

				$QueryParams->Add(
					new DatabaseQueryParam(":id_$key", $DemandeConges->id),
				);
			}
			if (str_ends_with2($query, "OR "))
			{
				$query = rtrim($query, "OR ");
			}

			$nb_inserts = $this->Database->Delete(
				$query,
				$QueryParams
			);

			return $nb_inserts === count($ArrayDemandeConges);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}

	public function EraseById(int $id): bool
	{
		try
		{
			return $this->Database->Delete(
				"DELETE f_demandecp WHERE F_DEMANDECP_ID = :id",
				new ListDatabaseQueryParam(
					new DatabaseQueryParam(":id", $id),
				)
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}

	#endregion

	#region debug request

	public function GetWithMissingUUID(): ListDemandeConges
	{
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					"SELECT F_DEMANDECP_ID FROM f_demandecp WHERE F_DEMANDECP_UUID = \"\""
				)
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	public function GenerateNewUUID(ListDemandeConges $ListDemandeConges): bool
	{
		try
		{
			foreach ($ListDemandeConges as $DemandeConges)
			{
				$nbRow = $this->Database->Update(
					"UPDATE f_demandecp SET F_DEMANDECP_UUID = :uuid WHERE F_DEMANDECP_ID = :id",
					new ListDatabaseQueryParam(
						new DatabaseQueryParam(":uuid", $DemandeConges->UUID, PDO::PARAM_STR),
						new DatabaseQueryParam(":id", $DemandeConges->id, PDO::PARAM_INT)
					)
				);

				if ($nbRow != 1)
				{
					throw new Exception("The DemandeConges with id = $DemandeConges->id was not updated properly, <br>"
						. "$nbRow was affected by the update, this behavior was unexcpected and may have caused data corruption");
				}
			}

			return true;
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}

	#endregion
}


