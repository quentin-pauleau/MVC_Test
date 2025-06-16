<?php
namespace Models\Entities;

use Models\Entities\Entity;
use Models\Entities\CDEFourH;
use Models\Entities\Fournisseur;
use Models\Entities\EtatModel;
use Models\ModelCDEFourL;

/**
 * A product of a 'commande fournisseur / commande stock'
 * 
 * model : {@see ModelCDEFourL}
 * list : {@see ListCDEFourL}
 * 
 * relations :
 * 	- {@see self::Cde} -> {@see CDEFourH}
 * 	- {@see self::Fournisseur} -> {@see Fournisseur}
 * 	- {@see self::Etat} -> {@see EtatModel}
 */
class CDEFourL extends Entity
{
	public ?int $id_cde = null;
	public CDEFourH $Cde;

	public ?int $id_fournisseur = null;
	public Fournisseur $Fournisseur;

	public ?int $id_etat = null;
	public EtatModel $Etat;

	public string $produit = "";
	public ?int $qte = null;
}


