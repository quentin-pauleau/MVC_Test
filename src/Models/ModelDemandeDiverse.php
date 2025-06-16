<?php
namespace Models;

use Exception;
use PDO;

use Traits\Singleton;

use Models\Entities\DemandeDiverse;
use Models\EntityLists\ListDemandeDiverse;

use Utils\Database\DatabaseException;
use Utils\Database\DatabaseQueryParam;
use Utils\Database\ListDatabaseQueryParam;
use Utils\UUID;


/**
 * This class is the model for the {@see DemandeDiverse} database object
 * 
 * database object : {@see DemandeDiverse}
 * list : {@see ListDemandeDiverse}
 */
class ModelDemandeDiverse extends Model
{
	use Singleton;

	public function NewObject(array $data): DemandeDiverse {
		$DemandeDiverse= new DemandeDiverse();
		$DemandeDiverse->id ??= $data["DEMANDES_ID"];

		if (isset($data["DEMANDES_DATE"]))
			$DemandeDiverse->SetCreatedAtFromString($data["DEMANDES_DATE"]);

		$DemandeDiverse->id_urgence ?? $data["DEMANDES_IDURGENCE"];
		$DemandeDiverse->id_etat ??= $data["DEMANDES_IDETAT"];
		$DemandeDiverse->id_destinataire ??= $data["DEMANDES_IDDESTINATAIRE"];
		$DemandeDiverse->id_demandeur ??= $data["DEMANDES_IDDEMANDEUR"];

		if (isset($data["DEMANDES_DEMANDE"]))
			$DemandeDiverse->demande = Convert_encoding_to_utf8($data["DEMANDES_DEMANDE"]);
		
		if (isset($data["DEMANDES_COMMENTAIRE"]))
			$DemandeDiverse->commentaire ??= Convert_encoding_to_utf8($data["DEMANDES_COMMENTAIRE"]);

		$DemandeDiverse->UUID ??= $data["DEMANDES_UUID"];

		$DemandeDiverse->id_conversation_thread ??= $data["DEMANDES_IDCONVERSATIONFIL"];

		if ($DemandeDiverse->id_conversation_thread == 0)
			$DemandeDiverse->id_conversation_thread = null;

		return $DemandeDiverse;
	}

	
	public function NewList(array $data): ListDemandeDiverse {
		$List = new ListDemandeDiverse();

		foreach ($data as $DemandeDiverse) {
			$List->Add($this->NewObject($DemandeDiverse));
		}

		return $List;
	}

	#region Selection Queries

	/**
	 * Summary of GetById
	 * @param int $id
	 * @return ?DemandeDiverse
	 */
	public function GetById(int $id): ?DemandeDiverse {
		try
		{
			$data = $this->Database->GetOne(
				'SELECT * FROM demandes WHERE DEMANDES_ID = :id', 
				new ListDatabaseQueryParam(
					new DatabaseQueryParam(':id', $id, PDO::PARAM_INT)
				)
			);

			if($data === [])
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
	 * @return ListDemandeDiverse
	 */
	public function GetAll() : ListDemandeDiverse {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					"SELECT * FROM demandes"
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
				'SELECT DEMANDES_ID FROM demandes WHERE DEMANDES_ID = :id', 
				new ListDatabaseQueryParam(
					new DatabaseQueryParam(':id', $id, PDO::PARAM_INT)
				)
			);
			
			return $data !== null;
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}
	

	/**
	 * Summary of GetById
	 * @param int $id
	 * @return ?DemandeDiverse
	 */
	public function GetByConversationThreadId(int $conversationThreadId): ?DemandeDiverse {
		try
		{
			return $this->NewObject(
				$this->Database->GetOne(
					"SELECT * FROM demandes 
					WHERE DEMANDES_IDCONVERSATIONFIL = :conversationThreadId
					", 
					new ListDatabaseQueryParam(
						new DatabaseQueryParam(":conversationThreadId", $conversationThreadId, PDO::PARAM_INT),
					)
				)
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}
	
	#endregion

	

	#region complex selection query

	/**
	 * Get all 'demandes clients' with the given 'demandeur'
	 * @param int $id_demandeur
	 * @return ListDemandeDiverse
	 */
	public function GetLike(DemandeDiverse $DemandeDiverse, ?array $state_ids = null) : ListDemandeDiverse {
		
		$query = 'SELECT * FROM demandes WHERE ';
		$ListDatabaseQueryParam = new ListDatabaseQueryParam;

		if ($DemandeDiverse->demande != '') {
			$query .= 'DEMANDES_DEMANDE LIKE :demand AND ';
			$ListDatabaseQueryParam->Add(
				new DatabaseQueryParam(':demand', "%$DemandeDiverse->demande%", PDO::PARAM_STR)
			);
		}

		if ($state_ids === null || $state_ids === []) {
			if ($DemandeDiverse->id_etat != null) {
				$query .= 'DEMANDES_IDETAT = :id_state AND ';
				$ListDatabaseQueryParam->Add(
					new DatabaseQueryParam(':id_state', $DemandeDiverse->id_etat, PDO::PARAM_INT)
				);
			}
		} else {
			$query .= '(';
			foreach ($state_ids as $key => $state_id) {
				$query .= "DEMANDES_IDETAT = :id_state_$key OR ";
				$ListDatabaseQueryParam->Add(
					new DatabaseQueryParam(":id_state_$key", $state_id, PDO::PARAM_INT)
				);
			}

			if (str_ends_with2($query, 'OR ')) {
				$query = substr($query, 0, -3);
			}

			$query .= ') AND ';
		}

		if ($DemandeDiverse->id_demandeur != null) {
			$query .= 'DEMANDES_IDDEMANDEUR = :id_demandeur AND ';
			$ListDatabaseQueryParam->Add(
				new DatabaseQueryParam(':id_demandeur', $DemandeDiverse->id_demandeur, PDO::PARAM_INT)
			);
		}

		if ($DemandeDiverse->id_destinataire != null) {
			$query .= 'DEMANDES_IDDESTINATAIRE = :id_destinataire AND ';
			$ListDatabaseQueryParam->Add(
				new DatabaseQueryParam(':id_destinataire', $DemandeDiverse->id_destinataire, PDO::PARAM_INT)
			);
		}

		if ($DemandeDiverse->id_urgence != null) {
			$query .= 'DEMANDES_IDURGENCE = :id_urgence AND ';
			$ListDatabaseQueryParam->Add(
				new DatabaseQueryParam(':id_urgence', $DemandeDiverse->id_urgence, PDO::PARAM_INT)
			);
		}

		if (str_ends_with2($query, 'WHERE '))
			$query = substr($query, 0, -6);
		elseif (str_ends_with2($query, 'AND '))
			$query = substr($query, 0, -4);

		try
		{
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

	#endregion complex selection query



	#region Modification Queries

	public function Insert(DemandeDiverse ...$ArrayDemande): bool {
		if ($ArrayDemande === [])
			return true;
		
		try
		{
			$nb_inserts = 0;
			
			foreach ($ArrayDemande as $DemandeDiverse) {
				$DemandeDiverse->id = $this->Database->InsertOne(
					"INSERT INTO demandes 
					(DEMANDES_DATE, DEMANDES_IDURGENCE, DEMANDES_IDETAT, DEMANDES_IDDEMANDEUR, DEMANDES_IDDESTINATAIRE, 
					DEMANDES_DEMANDE, DEMANDES_COMMENTAIRE, DEMANDES_UUID) 
					VALUES 
					(NOW(), :id_urgence, :id_etat, :id_dem, :id_dest, :demande, :commentaire, :UUID)",
					new ListDatabaseQueryParam(
						new DatabaseQueryParam(":id_urgence", $DemandeDiverse->id_urgence), 
						new DatabaseQueryParam(":id_etat", $DemandeDiverse->id_etat), 
						new DatabaseQueryParam(":id_dem", $DemandeDiverse->id_demandeur), 
						new DatabaseQueryParam(":id_dest", $DemandeDiverse->id_destinataire), 
						new DatabaseQueryParam(":demande", Convert_encoding_to_iso($DemandeDiverse->demande)), 
						new DatabaseQueryParam(":commentaire", Convert_encoding_to_iso($DemandeDiverse->commentaire)), 
						new DatabaseQueryParam(":UUID", $DemandeDiverse->UUID), 
					)
				);
				
				$nb_inserts++;
			}
			
			return $nb_inserts === count($ArrayDemande);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	public function InsertList(ListDemandeDiverse $List): bool {
		if ($List->IsEmpty())
			return true;
		
		try
		{
			$query = "INSERT INTO cdefour_l 
				(DEMANDES_DATE, DEMANDES_IDURGENCE, DEMANDES_IDETAT, DEMANDES_IDDEMANDEUR, DEMANDES_IDDESTINATAIRE, 
				DEMANDES_DEMANDE, DEMANDES_COMMENTAIRE, DEMANDES_UUID) 
				VALUES ";
			
			$QueryParams = new ListDatabaseQueryParam();

			foreach ($List as $key => $DemandeDiverse) {
				$query .= "(NOW(), :id_urgence_$key, id_etat_$key, :id_dem_$key, :id_dest_$key, 
				:demande_$key, :commentaire_$key, :UUID_$key), ";

				$QueryParams->Add(
					new DatabaseQueryParam(":id_urgence_$key", $DemandeDiverse->id_urgence), 
					new DatabaseQueryParam(":id_etat_$key", $DemandeDiverse->id_etat), 
					new DatabaseQueryParam(":id_dem_$key", $DemandeDiverse->id_demandeur), 
					new DatabaseQueryParam(":id_dest_$key", $DemandeDiverse->id_destinataire), 
					new DatabaseQueryParam(":demande_$key", Convert_encoding_to_iso($DemandeDiverse->demande)), 
					new DatabaseQueryParam(":commentaire_$key", Convert_encoding_to_iso($DemandeDiverse->commentaire)), 
					new DatabaseQueryParam(":UUID_$key", $DemandeDiverse->UUID), 
				);
			}
			if (str_ends_with2($query, ", "))
				$query = rtrim($query, ", ");

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
	 * Update DemandesDiverses
	 * @param \Models\Entities\DemandeDiverse[] $DemandesDiverses
	 * @return bool
	 */
	public function Update(DemandeDiverse ...$DemandesDiverses): bool {
		if ($DemandesDiverses === []) {
			return true;
		}
		
		try
		{
			$query = '';
			$QueryParams = new ListDatabaseQueryParam();

			foreach ($DemandesDiverses as $key => $DemandeDiverse) {
				$query .= "UPDATE demandes SET 
				DEMANDES_IDDEMANDEUR = :id_demandeur_$key, 
				DEMANDES_IDDESTINATAIRE = :id_destinataire_$key, 
				DEMANDES_IDETAT = :id_etat_$key, 
				DEMANDES_IDURGENCE = :id_urgence_$key, 
				DEMANDES_DEMANDE = :demande_$key, 
				DEMANDES_COMMENTAIRE = :commentaire_$key 
				WHERE DEMANDES_ID = :id_$key; ";
				
				$QueryParams->Add(
					new DatabaseQueryParam(":id_$key", $DemandeDiverse->id, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_demandeur_$key", $DemandeDiverse->id_demandeur, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_destinataire_$key", $DemandeDiverse->id_destinataire, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_etat_$key", $DemandeDiverse->id_etat, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_urgence_$key", $DemandeDiverse->id_urgence, PDO::PARAM_INT), 
					new DatabaseQueryParam(":demande_$key", convert_encoding_to_iso($DemandeDiverse->demande), PDO::PARAM_STR), 
					new DatabaseQueryParam(":commentaire_$key", convert_encoding_to_iso($DemandeDiverse->commentaire), PDO::PARAM_STR)
				);
			}

			$nb_updates = $this->Database->Update(
				$query,
				$QueryParams
			);

			return $nb_updates === count($DemandesDiverses);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	public function SetConversationThread(DemandeDiverse $DemandeDiverse): bool {
		try
		{
			return $this->Database->Update(
				"UPDATE demandes SET DEMANDES_IDCONVERSATIONFIL = :id_thread WHERE DEMANDES_ID = :id",
				new ListDatabaseQueryParam(
					new DatabaseQueryParam(":id_thread", $DemandeDiverse->id_conversation_thread, PDO::PARAM_STR),
					new DatabaseQueryParam(":id", $DemandeDiverse->id, PDO::PARAM_INT)
				)
			);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	
	public function Delete(DemandeDiverse ...$ArrayDemande): bool {
		if ($ArrayDemande === []) {
			return true;
		}

		
		try
		{
			$query = "DELETE demandes WHERE ";
			
			$QueryParams = new ListDatabaseQueryParam();

			foreach ($ArrayDemande as $key => $DemandeDiverse) {
				$query .= "DEMANDES_ID = :id_$key OR ";

				$QueryParams->Add(
					new DatabaseQueryParam(":id_$key", $DemandeDiverse->id), 
				);
			}
			if (str_ends_with2($query, "OR "))
				$query = rtrim($query, "OR ");

			$nb_inserts = $this->Database->Delete(
				$query,
				$QueryParams
			);

			return $nb_inserts === count($ArrayDemande);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}
	
	public function EraseById(int $id): bool {
		throw new Exception("Not implemented yet");
	}

	#endregion

	#region debug request

	public function GetWithMissingUUID() : ListDemandeDiverse {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					"SELECT DEMANDES_ID FROM demandes WHERE DEMANDES_UUID = \"\""
					)
				);
			}
			catch (DatabaseException $e)
			{
				throw $e;
			}
		}
	
	
	public function GenerateNewUUID(ListDemandeDiverse $ListDemandeDiverse): bool {
		try
		{
			foreach ($ListDemandeDiverse as $DemandeDiverse) {
				$DemandeDiverse->UUID = new UUID();
				
				$nbRow = $this->Database->Update(
					"UPDATE demandes SET DEMANDES_UUID = :uuid WHERE DEMANDES_ID = :id",
					new ListDatabaseQueryParam(
						new DatabaseQueryParam(":uuid", $DemandeDiverse->UUID, PDO::PARAM_STR),
						new DatabaseQueryParam(":id", $DemandeDiverse->id, PDO::PARAM_INT)
					)
				);
	
				if ($nbRow != 1)
					throw new Exception("The DemandeDiverse with id = $DemandeDiverse->id was not updated properly, <br>"
							."$nbRow was affected by the update, this behavior was unexcpected and may have caused data corruption");
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


