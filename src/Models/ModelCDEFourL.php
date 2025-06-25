<?php
namespace Models;

use Exception;
use PDO;

use Traits\Singleton;

use Models\Entities\CDEFourL;
use Models\EntityLists\ListCDEFourL;

use Core\Database\DatabaseException;
use Core\Database\DatabaseQueryParam;
use Core\Database\ListDatabaseQueryParam;

class ModelCDEFourL extends Model
{
	use Singleton;

	/**
	 * @param array $data
	 * @return CDEFourL
	 */
	public function NewObject(array $data): CDEFourL {
		$CDEFourL = new CDEFourL();

		$CDEFourL->id ??= $data["CDEFOUR_L_ID"];
                $CDEFourL->id_cde ??= $data["CDEFOUR_L_IDCDE"];
		$CDEFourL->id_fournisseur ??= $data["CDEFOUR_L_IDFOURNISSEUR"];
                $CDEFourL->produit = $data["CDEFOUR_L_PRODUIT"] ?? "";
                $CDEFourL->qte ??= $data["CDEFOUR_L_QTE"];
                $CDEFourL->id_etat ??= $data["CDEFOUR_L_IDETAT"];

		return $CDEFourL;
	}
	

	public function NewList(array $data): ListCDEFourL {
		$List = new ListCDEFourL();

		foreach ($data as $CDEFourL) {
			$List->Add($this->NewObject($CDEFourL));
		}

		return $List;
	}

	#region Commandes Fours selection queries 

	/**
	 * Get a commande stock product by a given id
	 * @param int $id
	 * @return Entities\CDEFourL|null
	 */
	public function GetById(int $id): ?CDEFourL {
		try
		{
			$data = $this->Database->GetOne(
				"SELECT * FROM cdefour_l WHERE CDEFOUR_L_ID = :id", 
				new ListDatabaseQueryParam(
					new DatabaseQueryParam(":id", $id),
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


	public function GetByCDEFourH(int $CdeFour_h_id) : ListCDEFourL {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					"SELECT * FROM cdefour_l WHERE CDEFOUR_L_IDCDE = :cde_h_id", 
					new ListDatabaseQueryParam(
						new DatabaseQueryParam(":cde_h_id", $CdeFour_h_id),
					)
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
	 * @return ListCDEFourL
	 */
	public function GetAll() : ListCDEFourL {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					"SELECT * FROM cdefour_l"
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
				"SELECT CDEFOUR_L_ID FROM cdefour_l WHERE CDEFOUR_L_ID = :id", 
				new ListDatabaseQueryParam(
					new DatabaseQueryParam(":id", $id),
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
	public function Insert(CDEFourL ...$ArrayCDEFourL): bool {
		if ($ArrayCDEFourL === []) {
			return true;
		}

		try
		{
			$query = "INSERT INTO cdefour_l (CDEFOUR_L_IDCDE, CDEFOUR_L_IDFOURNISSEUR, CDEFOUR_L_PRODUIT, CDEFOUR_L_QTE, CDEFOUR_L_IDETAT) 
				VALUES ";
			
			$QueryParams = new ListDatabaseQueryParam();

			foreach ($ArrayCDEFourL as $key => $CDEFourL) {
				$query .= "(:id_cde_h_$key, :id_four_$key, :nom_produit_$key, :qte_$key, :id_etat_$key), ";

				$QueryParams->Add(
					new DatabaseQueryParam(":id_cde_h_$key", $CDEFourL->id_cde, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_four_$key", $CDEFourL->id_fournisseur, PDO::PARAM_INT), 
					new DatabaseQueryParam(":nom_produit_$key", convert_encoding_to_iso($CDEFourL->produit), PDO::PARAM_STR), 
					new DatabaseQueryParam(":qte_$key", $CDEFourL->qte, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_etat_$key", $CDEFourL->id_etat, PDO::PARAM_INT), 
				);
			}
			if (str_ends_with2($query, ", ")) {
				$query = rtrim($query, ", ");
			}

			$nb_inserts = $this->Database->InsertList(
				$query,
				$QueryParams
			);

			return $nb_inserts === count($ArrayCDEFourL);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	public function InsertList(ListCDEFourL $List): bool {
		if ($List->IsEmpty()) {
			return true;
		}
		
		try
		{
			$query = "INSERT INTO cdefour_l (
				CDEFOUR_L_IDCDE, CDEFOUR_L_IDFOURNISSEUR, CDEFOUR_L_PRODUIT, CDEFOUR_L_QTE, CDEFOUR_L_IDETAT) 
				VALUES ";
			
			$QueryParams = new ListDatabaseQueryParam();

			foreach ($List as $key => $CDEFourL) {
				$query .= "(:id_cde_h_$key, :id_four_$key, :nom_produit_$key, :qte_$key, :id_etat_$key), ";

				$QueryParams->Add(
					new DatabaseQueryParam(":id_cde_h_$key", $CDEFourL->id_cde, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_four_$key", $CDEFourL->id_fournisseur, PDO::PARAM_INT), 
					new DatabaseQueryParam(":nom_produit_$key", convert_encoding_to_iso($CDEFourL->produit), PDO::PARAM_STR), 
					new DatabaseQueryParam(":qte_$key", $CDEFourL->qte, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_etat_$key", $CDEFourL->id_etat, PDO::PARAM_INT), 
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
	 * Summary of Update
	 * @param \Models\Entities\CDEFourL[] $CDEFourLs
	 * @return bool
	 */
	public function Update(CDEFourL ...$CDEFourLs): bool {
		if ($CDEFourLs === []) {
			return true;
		}
		
		try
		{
			$query = '';
			$QueryParams = new ListDatabaseQueryParam();

			foreach ($CDEFourLs as $key => $CDEFourL) {
				$query .= "UPDATE cdefour_l SET
				CDEFOUR_L_IDCDE = :id_cde_h_$key, 
				CDEFOUR_L_IDFOURNISSEUR = :id_four_$key, 
				CDEFOUR_L_IDETAT = :id_etat_$key, 
				CDEFOUR_L_PRODUIT = :nom_produit_$key, 
				CDEFOUR_L_QTE = :qte_$key
				WHERE CDEFOUR_L_ID = :id_$key; ";
				
				$QueryParams->Add(
					new DatabaseQueryParam(":id_$key", $CDEFourL->id, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_cde_h_$key", $CDEFourL->id_cde, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_four_$key", $CDEFourL->id_fournisseur, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_etat_$key", $CDEFourL->id_etat, PDO::PARAM_INT), 
					new DatabaseQueryParam(":nom_produit_$key", convert_encoding_to_iso($CDEFourL->produit), PDO::PARAM_STR), 
					new DatabaseQueryParam(":qte_$key", $CDEFourL->qte, PDO::PARAM_INT)
				);
			}

			$nb_updates = $this->Database->Update(
				$query,
				$QueryParams
			);

			return $nb_updates === count($CDEFourLs);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}
	
	
	public function Delete(ListCDEFourL ...$ArrayListCDEFourL): bool {
		throw new Exception("Not implemented yet");
	}
	
	
	public function EraseById(int $id): bool {
		throw new Exception("Not implemented yet");
	}

	#endregion
}


