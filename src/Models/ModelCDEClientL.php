<?php
namespace Models;

use Exception;
use PDO;

use Models\Model;

use Models\Entities\CDEClientL;
use Models\EntityLists\ListCDEClientL;

use Traits\Singleton;

use Core\Database\ListDatabaseQueryParam;
use Core\Database\DatabaseQueryParam;
use Core\Database\DatabaseException;


/**
 * Model for {@see CDEClientL}
 * 
 * {@see ModelCDEClientH} for the client order itself
 */
class ModelCDEClientL extends Model
{
	use Singleton;

	public function NewObject(array $data): CDEClientL {
		$CDEClientL = new CDEClientL();

		$CDEClientL->id ??= $data["CDECLIENT_L_ID"];
                $CDEClientL->id_cde ??= $data["CDECLIENT_L_IDCDE"];
                $CDEClientL->id_fournisseur ??= $data["CDECLIENT_L_IDFOURNISSEUR"];
                $CDEClientL->produit = isset($data["CDECLIENT_L_PRODUIT"]) ? convert_encoding_to_utf8($data["CDECLIENT_L_PRODUIT"]) : "";
                $CDEClientL->qte ??= $data["CDECLIENT_L_QTE"];
                $CDEClientL->id_etat ??= $data["CDECLIENT_L_IDETAT"];
                $CDEClientL->id_urgence ??= $data["CDECLIENT_L_IDURGENCE"];
		$CDEClientL->ok ??= $data["CDECLIENT_L_OK"];
		
		if (isset($data["CDECLIENT_L_COMMENTAIRE"]))
			$CDEClientL->demandeurComment = convert_encoding_to_utf8($data["CDECLIENT_L_COMMENTAIRE"]);

		return $CDEClientL;
	}

	
	public function NewList(array $data): ListCDEClientL {
		$List = new ListCDEClientL();

		foreach ($data as $CDEClientL)
			$List->Add($this->NewObject($CDEClientL));
		
		return $List;
	}


	#region Commandes Clients selection queries 

	/**
	 * Get a commande by a given id
	 * @param int $id the id used
	 * @throws \Exception not implemented yet
	 * @return never
	 */
	public function GetById(int $id): ?CDEClientL {
		try
		{
			$data = $this->Database->GetOne(
				"SELECT * FROM cdeclient_l WHERE CDECLIENT_L_ID = :id",
				new ListDatabaseQueryParam(
					new DatabaseQueryParam(":id", $id, PDO::PARAM_INT)
				)
			);

			if ($data !== [])
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
	 * @throws DatabaseException an exeception from
	 * @return ListCDEClientL
	 */
	public function GetAll() : ListCDEClientL {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					"SELECT * FROM cdeclient_l"
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
				"SELECT CDECLIENT_L_ID FROM cdeclient_l WHERE CDECLIENT_L_ID = :id",
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
	 * Get by commandes clients
	 * @param 
	 * @return ListCdeClientL
	 */
	public function GetByCDEClientH(int $CdeClient_h_id) : ListCdeClientL {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					"SELECT * FROM cdeclient_l WHERE CDECLIENT_L_IDCDE = :cde_h_id", 
					new ListDatabaseQueryParam(
						new DatabaseQueryParam(":cde_h_id", $CdeClient_h_id),
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


	#region Modification Queries
	public function Insert(CDEClientL ...$ArrayCDEClientL): bool {
		if ($ArrayCDEClientL === [])
			return true;

		try
		{
			$query = "INSERT INTO cdeclient_l 
				(CDECLIENT_L_IDCDE, CDECLIENT_L_IDFOURNISSEUR, CDECLIENT_L_IDETAT, CDECLIENT_L_PRODUIT, CDECLIENT_L_QTE, CDECLIENT_L_COMMENTAIRE) 
				VALUES ";
				// CDECLIENT_L_IDETAT, CDECLIENT_L_IDURGENCE, 
			
			$QueryParams = new ListDatabaseQueryParam();
			
			foreach ($ArrayCDEClientL as $key => $CDEClientL) {
				$query .= "(:id_cde_h_$key, :id_four_$key, :id_etat_$key, :nom_produit_$key, :qte_$key, :comdem_$key), ";
				// :id_etat_$key, :id_urgence_$key, 
				
				
				$QueryParams->Add(
				new DatabaseQueryParam(":id_cde_h_$key", $CDEClientL->id_cde, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_four_$key", $CDEClientL->id_fournisseur, PDO::PARAM_INT), 
					new DatabaseQueryParam(":nom_produit_$key", convert_encoding_to_iso($CDEClientL->produit), PDO::PARAM_STR), 
					new DatabaseQueryParam(":qte_$key", $CDEClientL->qte, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_etat_$key", $CDEClientL->id_etat, PDO::PARAM_INT), 
					// new DatabaseQueryParam(":id_urgence_$key", $CDEClientL->id_urgence, PDO::PARAM_INT), 
					new DatabaseQueryParam(":comdem_$key", convert_encoding_to_iso($CDEClientL->demandeurComment), PDO::PARAM_STR)
				);
			}
			
			if (str_ends_with2($query, ", ")) {
				$query = rtrim($query, ", ");
			}
			
			$nb_inserts = $this->Database->InsertList(
				$query,
				$QueryParams
			);
			
			return $nb_inserts === count($ArrayCDEClientL);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	public function InsertList(ListCDEClientL $List): bool {
		if ($List->IsEmpty()) {
			return true;
		}
		
		try
		{
			$query = "INSERT INTO cdeclient_l 
				(CDECLIENT_L_IDCDE, CDECLIENT_L_IDFOURNISSEUR, CDECLIENT_L_IDETAT, CDECLIENT_L_PRODUIT, CDECLIENT_L_QTE, CDECLIENT_L_COMMENTAIRE) 
				VALUES ";
			
			// CDECLIENT_L_IDETAT, CDECLIENT_L_IDURGENCE, 
			
			$QueryParams = new ListDatabaseQueryParam();

			foreach ($List as $key => $CDEClientL) {
				$query .= "(:id_cde_h_$key, :id_four_$key, :id_etat_$key, :nom_produit_$key, :qte_$key, :comdem_$key), ";
				// :id_etat_$key, :id_urgence_$key, 
				
				$QueryParams->Add(
				new DatabaseQueryParam(":id_cde_h_$key", $CDEClientL->id_cde, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_four_$key", $CDEClientL->id_fournisseur, PDO::PARAM_INT), 
					new DatabaseQueryParam(":nom_produit_$key", convert_encoding_to_iso($CDEClientL->produit), PDO::PARAM_STR), 
					new DatabaseQueryParam(":qte_$key", $CDEClientL->qte, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_etat_$key", $CDEClientL->id_etat, PDO::PARAM_INT), 
					// new DatabaseQueryParam(":id_urgence_$key", $CDEClientL->id_urgence, PDO::PARAM_INT), 
					new DatabaseQueryParam(":comdem_$key", convert_encoding_to_iso($CDEClientL->demandeurComment), PDO::PARAM_STR)
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
	 * 
	 * @param \Models\Entities\CDEClientL[] $CDEClientLs
	 * @return bool
	 */
	public function Update(CDEClientL ...$CDEClientLs): bool {
		if ($CDEClientLs === []) {
			return true;
		}
		
		try
		{
			$query = '';
			$QueryParams = new ListDatabaseQueryParam();

			foreach ($CDEClientLs as $key => $CDEClientL) {
				$query .= "UPDATE cdeclient_l SET
				CDECLIENT_L_IDCDE = :id_cde_h_$key, 
				CDECLIENT_L_IDFOURNISSEUR = :id_four_$key, 
				CDECLIENT_L_IDETAT = :id_etat_$key, 
				CDECLIENT_L_PRODUIT = :nom_produit_$key, 
				CDECLIENT_L_QTE = :qte_$key, 
				CDECLIENT_L_COMMENTAIRE = :comdem_$key
				WHERE CDECLIENT_L_ID = :id_$key; ";
				
				$QueryParams->Add(
					new DatabaseQueryParam(":id_$key", $CDEClientL->id, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_cde_h_$key", $CDEClientL->id_cde, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_four_$key", $CDEClientL->id_fournisseur, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_etat_$key", $CDEClientL->id_etat, PDO::PARAM_INT), 
					new DatabaseQueryParam(":nom_produit_$key", convert_encoding_to_iso($CDEClientL->produit), PDO::PARAM_STR), 
					new DatabaseQueryParam(":qte_$key", $CDEClientL->qte, PDO::PARAM_INT), 
					new DatabaseQueryParam(":comdem_$key", convert_encoding_to_iso($CDEClientL->demandeurComment), PDO::PARAM_STR)
				);
			}

			$nb_updates = $this->Database->Update(
				$query,
				$QueryParams
			);

			return $nb_updates === count($CDEClientLs);
		}
		catch (DatabaseException $e)
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
}


