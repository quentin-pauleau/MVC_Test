<?php
namespace Models;

use Exception;
use PDO;

use Traits\Singleton;

use Models\Entities\CDEFourH;
use Models\EntityLists\ListCDEFourH;

use Utils\Database\DatabaseException;
use Utils\Database\DatabaseQueryParam;
use Utils\Database\ListDatabaseQueryParam;
use Utils\UUID;


/**
 * This class is the model for the {@see CDEFourH} database object
 * 
 * database object : {@see CDEFourH}
 * list : {@see ListCDEFourH}
 */
class ModelCDEFourH extends Model
{
	use Singleton;

	public function NewObject(array $data): CDEFourH {
		$CDEFour = new CDEFourH();
		$CDEFour->id ??= $data["CDEFOUR_H_ID"];

		if (isset($data["CDEFOUR_H_DATE"]))
			$CDEFour->SetCreatedAtFromString($data["CDEFOUR_H_DATE"]);

		$CDEFour->id_etat ??= $data["CDEFOUR_H_ETAT"];
		$CDEFour->id_fournisseur ??= $data["CDEFOUR_H_IDFOURNISSEUR"];
		$CDEFour->id_destinataire ??= $data["CDEFOUR_H_IDDESTINATAIRE"];
		$CDEFour->id_demandeur ??= $data["CDEFOUR_H_IDDEMANDEUR"];
		
		$CDEFour->demandeurComment = isset($data["CDEFOUR_H_COMDEM2"]) ? $data["CDEFOUR_H_COMDEM2"] : '';
		$CDEFour->destinataireComment = isset($data["CDEFOUR_H_COMDEST2"]) ? $data["CDEFOUR_H_COMDEST2"] : '';

		$CDEFour->UUID ??= $data["CDEFOUR_H_UUID"];

		$CDEFour->id_conversation_thread ??= $data["CDEFOUR_H_IDCONVERSATIONFIL"];
		return $CDEFour;
	}

	
	public function NewList(array $data): ListCDEFourH {
		$List = new ListCDEFourH();

		foreach ($data as $User)
			$List->Add($this->NewObject($User));

		return $List;
	}

	
	#region Selection Queries 

	/**
	 * Summary of GetById
	 * @param int $id
	 * @return ?CDEFourH
	 */
	public function GetById(int $id): ?CDEFourH {
		try
		{
			$data = $this->Database->GetOne(
				"SELECT * FROM cdefour_h WHERE CDEFOUR_H_ID = :id", 
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
	 * @return ListCDEFourH
	 */
	public function GetAll() : ListCDEFourH {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					"SELECT * FROM cdefour_h"
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
				"SELECT CDEFOUR_H_ID FROM cdefour_h WHERE CDEFOUR_H_ID = :id", 
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
	 * Summary of GetById
	 * @param int $id
	 * @return ?CDEFourH
	 */
	public function GetByConversationThreadId(int $conversationThreadId): ?CDEFourH {
		try
		{
			return $this->NewObject(
				$this->Database->GetOne(
					"SELECT * FROM cdefour_h 
					WHERE CDEFOUR_H_IDCONVERSATIONFIL = :conversationThreadId
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
	 * @return ListCDEFourH
	 */
	public function GetLike(CDEFourH $CDEClientH, ?array $state_ids = null) : ListCDEFourH {
		try
		{
			//* build the query
			$query = 'SELECT * FROM cdefour_h WHERE ';
			$ListDatabaseQueryParam = new ListDatabaseQueryParam;

			if ($CDEClientH->id_fournisseur != '') {
				$query .= 'CDEFOUR_H_IDFOURNISSEUR = :id_fournisseur AND ';
				$ListDatabaseQueryParam->Add(
					new DatabaseQueryParam(':id_fournisseur', $CDEClientH->id_fournisseur, PDO::PARAM_STR)
				);
			}

			if ($state_ids === null || $state_ids === []) {
				if ($CDEClientH->id_etat != null) {
					$query .= 'CDEFOUR_H_ETAT = :id_state AND ';
					$ListDatabaseQueryParam->Add(
						new DatabaseQueryParam(':id_state', $CDEClientH->id_etat, PDO::PARAM_INT)
					);
				}
			} else {
				$query .= '(';
				foreach ($state_ids as $key => $state_id) {
					$query .= "CDEFOUR_H_ETAT = :id_state_$key OR ";
					$ListDatabaseQueryParam->Add(
						new DatabaseQueryParam(":id_state_$key", $state_id, PDO::PARAM_INT)
					);
				}
	
				if (str_ends_with2($query, 'OR ')) {
					$query = substr($query, 0, -3);
				}
	
				$query .= ') AND ';
			}

			if ($CDEClientH->id_demandeur != null) {
				$query .= 'CDEFOUR_H_IDDEMANDEUR = :id_demandeur AND ';
				$ListDatabaseQueryParam->Add(
					new DatabaseQueryParam(':id_demandeur', $CDEClientH->id_demandeur, PDO::PARAM_INT)
				);
			}

			if ($CDEClientH->id_destinataire != null) {
				$query .= 'CDEFOUR_H_IDDESTINATAIRE = :id_destinataire AND ';
				$ListDatabaseQueryParam->Add(
					new DatabaseQueryParam(':id_destinataire', $CDEClientH->id_destinataire, PDO::PARAM_INT)
				);
			}

			//* cut the additional key word
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

	#endregion complex selection query



	#region Modification Queries 
	public function Insert(CDEFourH ...$ArrayCDEFourH): bool {
		if ($ArrayCDEFourH === [])
			return true;
		
		try
		{
			$nb_inserts = 0;
			
			foreach ($ArrayCDEFourH as $key => $CDEFourH) {
				$CDEFourH->id = $this->Database->InsertOne(
					"INSERT INTO cdefour_h 
					(CDEFOUR_H_DATE, CDEFOUR_H_IDFOURNISSEUR, CDEFOUR_H_IDDESTINATAIRE, CDEFOUR_H_IDDEMANDEUR, CDEFOUR_H_ETAT,
					CDEFOUR_H_COMDEM2, CDEFOUR_H_UUID) 
					VALUES 
					(NOW(), :id_four, :id_dest, :id_dem, :id_state, :demandeurComment, :UUID)",
					new ListDatabaseQueryParam(
						new DatabaseQueryParam(":id_four", $CDEFourH->id_fournisseur, PDO::PARAM_INT), 
						new DatabaseQueryParam(":id_dest", $CDEFourH->id_destinataire, PDO::PARAM_INT), 
						new DatabaseQueryParam(":id_dem", $CDEFourH->id_demandeur, PDO::PARAM_INT), 
						new DatabaseQueryParam(":id_state", $CDEFourH->id_etat, PDO::PARAM_INT), 
						new DatabaseQueryParam(":demandeurComment", $CDEFourH->demandeurComment, PDO::PARAM_STR), 
						new DatabaseQueryParam(":UUID", $CDEFourH->UUID, PDO::PARAM_STR), 
					)
				);

				$nb_inserts++;
			}
			
			return $nb_inserts === count($ArrayCDEFourH);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	public function InsertList(ListCDEFourH $List): bool {
		if ($List->IsEmpty()) {
			return true;
		}
		
		try
		{
			$query = "INSERT INTO cdefour_l 
				(CDEFOUR_H_DATE, CDEFOUR_H_IDFOURNISSEUR, CDEFOUR_H_IDDESTINATAIRE, CDEFOUR_H_IDDEMANDEUR, CDEFOUR_H_ETAT, CDEFOUR_H_COMDEM2, CDEFOUR_H_UUID) 
				VALUES ";
			
			$QueryParams = new ListDatabaseQueryParam();
			
			foreach ($List as $key => $CDEFourH) {
				$query .= "(NOW(), :id_four_$key, :id_dest_$key, :id_dem_$key, :id_etat_$key, :demandeurComment_$key, :UUID_$key), ";
				
				$QueryParams->Add(
					new DatabaseQueryParam(":id_four_$key ", $CDEFourH->id_fournisseur, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_dest_$key ", $CDEFourH->id_destinataire, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_dem_$key ", $CDEFourH->id_demandeur, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_etat_$key ", $CDEFourH->id_etat, PDO::PARAM_INT), 
					new DatabaseQueryParam(":demandeurComment_$key", $CDEFourH->demandeurComment, PDO::PARAM_STR), 
					new DatabaseQueryParam(":UUID_$key", $CDEFourH->UUID, PDO::PARAM_STR), 
				);
			}
			
			if (str_ends_with2($query, ", ")) {
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
	 * Update the commande stock given
	 * @param \Models\Entities\CDEFourH[] $CDEFourHs
	 * @return bool
	 */
	public function Update(CDEFourH ...$CDEFourHs): bool {
		if ($CDEFourHs === []) {
			return true;
		}
		
		try
		{
			$query = '';
			$QueryParams = new ListDatabaseQueryParam();

			foreach ($CDEFourHs as $key => $CDEFourH) {
				$query .= "UPDATE cdefour_h SET 
				CDEFOUR_H_IDDESTINATAIRE = :id_dest_$key, 
				CDEFOUR_H_IDDEMANDEUR = :id_dem_$key, 
				CDEFOUR_H_ETAT = :id_etat_$key, 
				CDEFOUR_H_IDFOURNISSEUR = :id_fournisseur_$key, 
				CDEFOUR_H_COMDEM2 = :comdem_$key
				WHERE CDEFOUR_H_ID = :id_$key; ";
				
				$QueryParams->Add(
					new DatabaseQueryParam(":id_$key", $CDEFourH->id, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_dest_$key", $CDEFourH->id_destinataire, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_dem_$key", $CDEFourH->id_demandeur, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_etat_$key", $CDEFourH->id_etat, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_fournisseur_$key", $CDEFourH->id_fournisseur, PDO::PARAM_INT), 
					new DatabaseQueryParam(":comdem_$key", convert_encoding_to_iso($CDEFourH->demandeurComment), PDO::PARAM_STR)
				);
			}

			$nb_updates = $this->Database->Update(
				$query,
				$QueryParams
			);

			return $nb_updates === count($CDEFourHs);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	public function SetConversationThread(CDEFourH $CDEFourH): bool {
		try
		{
			return $this->Database->Update(
				"UPDATE cdefour_h SET CDEFOUR_H_IDCONVERSATIONFIL = :id_thread WHERE CDEFOUR_H_ID = :id",
				new ListDatabaseQueryParam(
					new DatabaseQueryParam(":id_thread", $CDEFourH->id_conversation_thread, PDO::PARAM_STR),
					new DatabaseQueryParam(":id", $CDEFourH->id, PDO::PARAM_INT)
				)
			);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	
	public function Delete(CDEFourH ...$CDEFourH): bool {
		throw new Exception("Not implemented yet");
	}
	
	public function EraseById(int $id): bool {
		throw new Exception("Not implemented yet");
	}

	#endregion


	#region debug request 

	public function GetWithMissingUUID() : ListCDEFourH {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					"SELECT CDEFOUR_H_ID FROM cdefour_h WHERE CDEFOUR_H_UUID = \"\""
					)
				);
			}
			catch (DatabaseException $e)
			{
				throw $e;
			}
		}
	
	
	public function GenerateNewUUID(ListCDEFourH $ListCDEFourH): bool {
		try
		{
			foreach ($ListCDEFourH as $CDEFourH) {
				$CDEFourH->UUID = new UUID();
				
				$nbRow = $this->Database->Update(
					"UPDATE cdefour_h SET CDEFOUR_H_UUID = :uuid WHERE CDEFOUR_H_ID = :id",
					new ListDatabaseQueryParam(
						new DatabaseQueryParam(":uuid", $CDEFourH->UUID, PDO::PARAM_STR),
						new DatabaseQueryParam(":id", $CDEFourH->id, PDO::PARAM_INT)
					)
				);
				
				if ($nbRow != 1) {
					throw new Exception("The CDEFourH with id = $CDEFourH->id was not updated properly, <br>"
							."$nbRow was affected by the update, this behavior was unexcpected and may have caused data corruption");
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


