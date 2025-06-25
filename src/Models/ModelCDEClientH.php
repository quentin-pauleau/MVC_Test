<?php
namespace Models;

use DateTime;
use Exception;
use PDO;

use Traits\Singleton;

use Models\Model;

use Models\Entities\CDEClientH;
use Models\EntityLists\ListCDEClientH;

use Core\Database\DatabaseQueryParam;
use Core\Database\ListDatabaseQueryParam;
use Core\Database\DatabaseException;
use Core\UUID;


/**
 * Model for Client's Order (CDEClientH)
 * 
 * {@see ModelCDEClientL} for the products of the client's order
 * {@see ModelCDEFourH} for fournisseur's order
 */
class ModelCDEClientH extends Model
{
	use Singleton;

	public function NewObject(array $data): CDEClientH {
		$CDEClientH = new CDEClientH();

		$CDEClientH->id = $data["CDECLIENT_H_ID"] ?? null;

		if (isset($data["CDECLIENT_H_DATE"])) {
			$CDEClientH->CreatedAt = new DateTime($data["CDECLIENT_H_DATE"]);
		}
		
		$CDEClientH->nom_client = isset($data["CDECLIENT_H_NOMCLIENT"]) ? convert_encoding_to_utf8($data["CDECLIENT_H_NOMCLIENT"]) : "";

		$CDEClientH->id_destinataire = $data["CDECLIENT_H_IDDESTINATAIRE"] ?? null;
		$CDEClientH->id_demandeur = $data["CDECLIENT_H_IDDEMANDEUR"] ?? null;
		$CDEClientH->id_urgence = $data["CDECLIENT_H_IDURGENCE"] ?? null;
		$CDEClientH->id_etat = $data["CDECLIENT_H_ETAT"] ?? null;
		$CDEClientH->id_type_livraison = $data["CDECLIENT_H_IDTYPELIVRAISON"] ?? null;

		$CDEClientH->cde = $data["CDECLIENT_H_CDE"] ?? null;
		$CDEClientH->devis =  $data["CDECLIENT_H_DEVIS"] ?? null;
		$CDEClientH->demandeurComment = isset($data["CDECLIENT_H_COMDEM"]) ? convert_encoding_to_utf8($data["CDECLIENT_H_COMDEM"]) : "";
		$CDEClientH->destinataireComment = isset($data["CDECLIENT_H_COM_DEST"]) ? convert_encoding_to_utf8($data["CDECLIENT_H_COM_DEST"]) : "";

		$CDEClientH->id_conversation_thread = $data["CDECLIENT_H_IDCONVERSATIONFIL"] ?? null;

		return $CDEClientH;
	}

	
	public function NewList(array $data): ListCDEClientH {
		$List = new ListCDEClientH();

		foreach ($data as $User)
			$List->Add(self::NewObject($User));

		return $List;
	}


	#region Commandes Clients H selection queries 

	/**
	 * Summary of GetById
	 * @param int $id
	 * @return ?CDEClientH
	 */
	public function GetById(int $id): ?CDEClientH {
		try
		{
			$data = $this->Database->GetOne(
				"SELECT * FROM cdeclient_h WHERE CDECLIENT_H_ID = :id", 
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
	 * Get all 'commandes clients'
	 * @param 
	 * @return ListCDEClientH
	 */
	public function GetAll() : ListCDEClientH {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					"SELECT * FROM cdeclient_h"
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
				"SELECT CDECLIENT_H_ID FROM cdeclient_h WHERE CDECLIENT_H_ID = :id", 
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
	 * Summary of GetById
	 * @param int $id
	 * @return ?CDEClientH
	 */
	public function GetByConversationThreadId(int $conversationThreadId): ?CDEClientH {
		try
		{
			$data = $this->Database->GetOne(
				"SELECT * FROM cdeclient_h 
				WHERE CDECLIENT_H_IDCONVERSATIONFIL = :conversationThreadId
				", 
				new ListDatabaseQueryParam(
					new DatabaseQueryParam(":conversationThreadId", $conversationThreadId, PDO::PARAM_INT),
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

	
	#endregion


	#region complex selection query

	/**
	 * Get all 'demandes clients' with the given 'demandeur'
	 * @param int $id_demandeur
	 * @param int[] $state_ids
	 * @return ListCDEClientH
	 */
	public function GetLike(CDEClientH $CDEClientH, ?array $state_ids = null) : ListCDEClientH {
		try
		{
			$query = 'SELECT * FROM cdeclient_h WHERE ';
			$ListDatabaseQueryParam = new ListDatabaseQueryParam;

			if ($CDEClientH->nom_client != '') {
				$query .= 'CDECLIENT_H_NOMCLIENT LIKE :nom_client AND ';
				$ListDatabaseQueryParam->Add(
					new DatabaseQueryParam(':nom_client', "%$CDEClientH->nom_client%", PDO::PARAM_STR)
				);
			}


			if ($state_ids === null || $state_ids === []) {
				if ($CDEClientH->id_etat != null) {
					$query .= 'CDECLIENT_H_ETAT = :id_state AND ';
					$ListDatabaseQueryParam->Add(
						new DatabaseQueryParam(':id_state', $CDEClientH->id_etat, PDO::PARAM_INT)
					);
				}
			} else {
				$query .= '(';
				foreach ($state_ids as $key => $state_id) {
					$query .= "CDECLIENT_H_ETAT = :id_state_$key OR ";
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
				$query .= 'CDECLIENT_H_IDDEMANDEUR = :id_demandeur AND ';
				$ListDatabaseQueryParam->Add(
					new DatabaseQueryParam(':id_demandeur', $CDEClientH->id_demandeur, PDO::PARAM_INT)
				);
			}

			if ($CDEClientH->id_destinataire != null) {
				$query .= 'CDECLIENT_H_IDDESTINATAIRE = :id_destinataire AND ';
				$ListDatabaseQueryParam->Add(
					new DatabaseQueryParam(':id_destinataire', $CDEClientH->id_destinataire, PDO::PARAM_INT)
				);
			}

			if ($CDEClientH->id_urgence != null) {
				$query .= 'CDECLIENT_H_IDURGENCE = :id_urgence AND ';
				$ListDatabaseQueryParam->Add(
					new DatabaseQueryParam(':id_urgence', $CDEClientH->id_urgence, PDO::PARAM_INT)
				);
			}

			if ($CDEClientH->id_type_livraison != null) {
				$query .= 'CDECLIENT_H_IDTYPELIVRAISON = :id_type_livraison AND ';
				$ListDatabaseQueryParam->Add(
					new DatabaseQueryParam(':id_type_livraison', $CDEClientH->id_type_livraison, PDO::PARAM_INT)
				);
			}

			if ($CDEClientH->cde != null) {
				$query .= 'CDECLIENT_H_CDE = :cde AND ';
				$ListDatabaseQueryParam->Add(
					new DatabaseQueryParam(':cde', $CDEClientH->cde, PDO::PARAM_BOOL)
				);
			}

			if ($CDEClientH->devis != null) {
				$query .= 'CDECLIENT_H_DEVIS = :devis AND ';
				$ListDatabaseQueryParam->Add(
					new DatabaseQueryParam(':devis', $CDEClientH->devis, PDO::PARAM_BOOL)
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

	#endregion complex selection query


	#region Count Queries
	public function CountAll(): ?int {
		try
		{
			return $this->Database->GetOne(
				"SELECT COUNT(CDECLIENT_H_ID) AS 'count' FROM cdeclient_h"
			)['count'];
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}

	
	public function CountCommandes(): int {
		try
		{
			return $this->Database->GetOne(
				"SELECT COUNT(CDECLIENT_H_ID) AS 'count' FROM cdeclient_h WHERE CDECLIENT_H_CDE = 1"
			)['count'];
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	public function CountDevis(): int {
		try
		{
			return $this->Database->GetOne(
				"SELECT COUNT(CDECLIENT_H_ID) AS 'count' FROM cdeclient_h WHERE CDECLIENT_H_DEVIS = 1"
			)['count'];
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	public function CountCommandesByDemandeurId(int $id_demandeur): int {
		try
		{
			return $this->Database->GetOne(
				"SELECT COUNT(CDECLIENT_H_ID) AS 'count' FROM cdeclient_h WHERE CDECLIENT_H_CDE = 1 AND CDECLIENT_H_IDDEMANDEUR = :id_demandeur",
				new ListDatabaseQueryParam(
					new DatabaseQueryParam(":id_demandeur", $id_demandeur, PDO::PARAM_INT)
				)
			)['count'];
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	public function CountDevisByDemandeurId(int $id_demandeur): int {
		try
		{
			return $this->Database->GetOne(
				"SELECT COUNT(CDECLIENT_H_ID) AS 'count' FROM cdeclient_h WHERE CDECLIENT_H_DEVIS = 1 AND CDECLIENT_H_IDDEMANDEUR = :id_demandeur",
				new ListDatabaseQueryParam(
					new DatabaseQueryParam(":id_demandeur", $id_demandeur, PDO::PARAM_INT)
				)
			)['count'];
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	public function CountCommandesByDestinataireId(int $id_destinataire): int {
		try
		{
			return $this->Database->GetOne(
				"SELECT COUNT(CDECLIENT_H_ID) AS 'count' FROM cdeclient_h WHERE CDECLIENT_H_CDE = 1 AND CDECLIENT_H_IDDESTINATAIRE = :id_destinataire",
				new ListDatabaseQueryParam(
					new DatabaseQueryParam(":id_destinataire", $id_destinataire, PDO::PARAM_INT)
				)
			)['count'];
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}

	
	public function CountDevisByDestinataireId(int $id_destinataire): int {
		try
		{
			return $this->Database->GetOne(
				"SELECT COUNT(CDECLIENT_H_ID) AS 'count' FROM cdeclient_h WHERE CDECLIENT_H_DEVIS = 1 AND CDECLIENT_H_IDDESTINATAIRE = :id_destinataire",
				new ListDatabaseQueryParam(
					new DatabaseQueryParam(":id_destinataire", $id_destinataire, PDO::PARAM_INT)
				)
			)['count'];
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	public function CountCommandesByDemandeurIdOrDestinataireId(int $id_demandeur, int $id_destinataire): int {
		try
		{
			return $this->Database->GetOne(
				"SELECT COUNT(CDECLIENT_H_ID) AS 'count' FROM cdeclient_h WHERE CDECLIENT_H_CDE = 1 
				AND (CDECLIENT_H_IDDEMANDEUR = :id_demandeur OR CDECLIENT_H_IDDESTINATAIRE = :id_destinataire)",
				new ListDatabaseQueryParam(
					new DatabaseQueryParam(":id_demandeur", $id_demandeur, PDO::PARAM_INT),
					new DatabaseQueryParam(":id_destinataire", $id_destinataire, PDO::PARAM_INT)
				)
			)['count'];
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}

	public function CountDevisByDemandeurIdOrDestinataireId(int $id_demandeur, int $id_destinataire): int {
		try
		{
			return $this->Database->GetOne(
				"SELECT COUNT(CDECLIENT_H_ID) AS 'count' FROM cdeclient_h WHERE CDECLIENT_H_DEVIS = 1 
				AND (CDECLIENT_H_IDDEMANDEUR = :id_demandeur OR CDECLIENT_H_IDDESTINATAIRE = :id_destinataire)",
				new ListDatabaseQueryParam(
					new DatabaseQueryParam(":id_demandeur", $id_demandeur, PDO::PARAM_INT),
					new DatabaseQueryParam(":id_destinataire", $id_destinataire, PDO::PARAM_INT)
				)
			)['count'];
			
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}

	#endregion




	#region Modification Queries
	/**
	 * Summary of Insert
	 * @param \Models\Entities\CDEClientH[] $ArrayCDEClientH
	 * @return bool
	 */
	public function Insert(CDEClientH ...$ArrayCDEClientH): bool {
		try
		{
			foreach ($ArrayCDEClientH as $CDEClientH) {
				$CDEClientH->id = $this->Database->InsertOne(
					"INSERT INTO cdeclient_h (
					CDECLIENT_H_DATE, 
					CDECLIENT_H_IDDESTINATAIRE, CDECLIENT_H_IDDEMANDEUR, CDECLIENT_H_IDURGENCE, CDECLIENT_H_IDTYPELIVRAISON, 
					CDECLIENT_H_ETAT,
					CDECLIENT_H_NOMCLIENT, CDECLIENT_H_CDE, CDECLIENT_H_DEVIS, 
					CDECLIENT_H_COMDEM, CDECLIENT_H_UUID
					) 
					VALUES 
					(NOW(), :id_dest, :id_dem, :id_urg, :id_type_livr, :id_etat, 
					:nom_cli, :cde, :devis, :comdem, :uuid)",
					new ListDatabaseQueryParam (
						new DatabaseQueryParam(":id_dest", $CDEClientH->id_destinataire, PDO::PARAM_INT), 
						new DatabaseQueryParam(":id_dem", $CDEClientH->id_demandeur, PDO::PARAM_INT), 
						new DatabaseQueryParam(":id_urg", $CDEClientH->id_urgence, PDO::PARAM_INT), 
						new DatabaseQueryParam(":id_type_livr", $CDEClientH->id_type_livraison, PDO::PARAM_INT), 
						new DatabaseQueryParam(":id_etat", $CDEClientH->id_etat, PDO::PARAM_INT), 
						new DatabaseQueryParam(":nom_cli", convert_encoding_to_iso($CDEClientH->nom_client), PDO::PARAM_STR), 
						new DatabaseQueryParam(":cde", $CDEClientH->cde, PDO::PARAM_BOOL), 
						new DatabaseQueryParam(":devis", $CDEClientH->devis, PDO::PARAM_BOOL), 
						new DatabaseQueryParam(":comdem", convert_encoding_to_iso($CDEClientH->demandeurComment), PDO::PARAM_STR),
						new DatabaseQueryParam(":uuid", convert_encoding_to_iso($CDEClientH->UUID), PDO::PARAM_STR),
					)
				);

				$CDEClientH->CreatedAt = new DateTime(
					$this->Database->GetOne(
						"SELECT CDECLIENT_H_DATE FROM cdeclient_h WHERE CDECLIENT_H_ID = :id",
						new ListDatabaseQueryParam(
							new DatabaseQueryParam(":id", $CDEClientH->id, PDO::PARAM_INT)
						)
					)['CDECLIENT_H_DATE']
				);
			}
			return true;
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	/**
	 * Update the users
	 * @param \Models\Entities\CDEClientH[] $CDEClientHs
	 * @return bool
	 */
	public function Update(CDEClientH ...$CDEClientHs): bool {
		if ($CDEClientHs === []) {
			return true;
		}
		
		try
		{
			$query = '';
			$QueryParams = new ListDatabaseQueryParam();

			foreach ($CDEClientHs as $key => $CDEClientH) {
				$query .= "UPDATE cdeclient_h SET 
				CDECLIENT_H_IDDESTINATAIRE = :id_dest_$key, 
				CDECLIENT_H_IDDEMANDEUR = :id_dem_$key, 
				CDECLIENT_H_IDURGENCE = :id_urg_$key, 
				CDECLIENT_H_IDTYPELIVRAISON = :id_type_livr_$key, 
				CDECLIENT_H_ETAT = :id_etat_$key, 
				CDECLIENT_H_NOMCLIENT = :nom_cli_$key, 
				CDECLIENT_H_CDE = :cde_$key, 
				CDECLIENT_H_DEVIS = :devis_$key, 
				CDECLIENT_H_COMDEM = :comdem_$key
				WHERE CDECLIENT_H_ID = :id_$key; ";
				
				$QueryParams->Add(
					new DatabaseQueryParam(":id_$key", $CDEClientH->id, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_dest_$key", $CDEClientH->id_destinataire, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_dem_$key", $CDEClientH->id_demandeur, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_urg_$key", $CDEClientH->id_urgence, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_type_livr_$key", $CDEClientH->id_type_livraison, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_etat_$key", $CDEClientH->id_etat, PDO::PARAM_INT), 
					new DatabaseQueryParam(":nom_cli_$key", convert_encoding_to_iso($CDEClientH->nom_client), PDO::PARAM_STR), 
					new DatabaseQueryParam(":cde_$key", $CDEClientH->cde, PDO::PARAM_BOOL), 
					new DatabaseQueryParam(":devis_$key", $CDEClientH->devis, PDO::PARAM_BOOL), 
					new DatabaseQueryParam(":comdem_$key", convert_encoding_to_iso($CDEClientH->demandeurComment), PDO::PARAM_STR)
				);
			}

			$nb_updates = $this->Database->Update(
				$query,
				$QueryParams
			);

			return $nb_updates === count($CDEClientHs);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	public function SetConversationThread(CDEClientH $CDEClientH): bool {
		try
		{
			return $this->Database->Update(
				"UPDATE cdeclient_h SET CDECLIENT_H_IDCONVERSATIONFIL = :id_thread WHERE CDECLIENT_H_ID = :id",
				new ListDatabaseQueryParam(
					new DatabaseQueryParam(":id_thread", $CDEClientH->id_conversation_thread, PDO::PARAM_STR),
					new DatabaseQueryParam(":id", $CDEClientH->id, PDO::PARAM_INT)
				)
			);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
	
	
	public function Delete(): bool {
		throw new Exception("Not implemented yet");
	}
	
	
	public function EraseById(int $id): bool {
		throw new Exception("Not implemented yet");
	}

	#endregion


	#region debug request

	public function GetWithMissingUUID() : ListCDEClientH {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					"SELECT CDECLIENT_H_ID FROM cdeclient_h WHERE CDECLIENT_H_UUID = \"\""
				)
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	public function GenerateNewUUID(ListCDEClientH $ListCDEClientH): bool {
		try
		{
			foreach ($ListCDEClientH as $CDEClientH) {
				$CDEClientH->UUID = new UUID();
				
				$nbRow = $this->Database->Update(
					"UPDATE cdeclient_h SET CDECLIENT_H_UUID = :uuid WHERE CDECLIENT_H_ID = :id",
					new ListDatabaseQueryParam(
						new DatabaseQueryParam(":uuid", $CDEClientH->UUID, PDO::PARAM_STR),
						new DatabaseQueryParam(":id", $CDEClientH->id, PDO::PARAM_INT)
					)
				);

				if ($nbRow != 1) {
					throw new Exception("The CDEClientH with id = $CDEClientH->id was not updated properly, <br>"
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


	public function GetWithMissingConversation() : ListCDEClientH {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					'SELECT CDECLIENT_H_ID FROM cdeclient_h WHERE CDECLIENT_H_IDCONVERSATIONFIL = null'
				)
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}

	#endregion
}


