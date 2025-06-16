<?php
namespace Models\Entities;

use Models\Entities\Entity;
use Models\Entities\CDEClientH;
use Models\Entities\Fournisseur;
use Models\Entities\EtatModel;
use Models\Entities\DegresUrgence;
use Models\ModelCDEClientL;


/**
 * A product of a client order
 * 
 * model : {@see ModelCDEClientL}
 * 
 * list : {@see ListCDEClientL}
 * 
 * 
 * relations :<br>
 * 	- {@see self::CDEClientH} -> {@see CDEClientH}
 * 	- {@see self::Fournisseur} -> {@see Fournisseur}
 * 	- {@see self::Etat} -> {@see EtatModel}
 * 	- {@see self::Urgence} -> {@see DegresUrgence}
 */
class CDEClientL extends Entity
{
	public ?int $id_cde = null;
	public CDEClientH $CDEClientH;

	public ?int $id_fournisseur = null;
	public Fournisseur $Fournisseur;

	public ?int $id_etat = null;
	public EtatModel $Etat;
	
	public ?int $id_urgence = null;
	public DegresUrgence $Urgence;

	public string $produit = "";
	public ?int $qte = null;
	public bool $ok = false;
	public string $demandeurComment = "";
}