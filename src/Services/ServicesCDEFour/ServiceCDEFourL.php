<?php
namespace Services\ServicesCDEFour;

use Enums\DemandeProductStates;
use Exception;

use Interfaces\ServiceInterface;

use Models\Entities\CDEClientH;
use Models\Entities\CDEFourL;

use Models\EntityLists\ListCDEClientL;
use Models\EntityLists\ListCDEFourL;
use Models\ModelCDEFourL;
use Models\ModelEtatModel;
use Models\ModelFournisseur;

use UnexpectedValueException;
use Utils\Session\ErrorHelper;


/**
 * 
 */
class ServiceCDEFourL implements ServiceInterface
{
	public function __construct() { }

	
	public function Find(int $id): CDEFourL {
		$Product = ModelCDEFourL::GetInstance()->GetById($id);

		if ($Product === null)
			throw new Exception("Product (CDEFourL) not found with \"id = $id\"");

		return $Product;
	}


	public function FindAll(): ListCDEFourL {
		return ModelCDEFourL::GetInstance()->GetAll();
	}
	
	
	public function FindByDemand(int $demandId): ListCDEFourL {
		return ModelCDEFourL::GetInstance()->GetByCDEFourH($demandId);
	}
	

	public function Any(int $id): bool {
		return ModelCDEFourL::GetInstance()->Any($id);
	}


	public function New(CDEFourL $Product): CDEFourL {
		$Product = clone $Product;
		$Product->id_etat = DemandeProductStates::DEFAULT;

		if (ModelCDEFourL::GetInstance()->Insert($Product))
			throw new Exception('Unable to insert the product of the demand fournisseur/stock');
			
		return $this->Find($Product->id);
	}

	
	public function AssociateAllReferences(CDEFourL $Product): bool {
		try
		{
			if ($Product->id_cde != null)
				$this->AssociateCDEFourH($Product);

			if ($Product->id_fournisseur != null)
				$this->AssociateFournisseur($Product);

			if ($Product->id_etat != null)
				$this->AssociateState($Product);
		}
		catch (Exception $e)
		{
			ErrorHelper::SetDefault('AssociateAllReferencesCDEFourL', 'Une erreur est survenue lors de la récupération des informations d\'un produit de la commande stock');
			ErrorHelper::SetDebug('AssociateAllReferencesCDEFourL', $e);
			return false;
		}

		return true;
	}

	public function AssociateCDEFourH(CDEFourL $CDEFourL): bool {
		if ($CDEFourL->id_cde === null)
			throw new UnexpectedValueException('The demande does not have a cde_h (order) fournisseur/stock');

		$CDEFourL->Cde = (new ServiceCDEFourH)->Find($CDEFourL->id_cde);

		return true;
	}

	public function AssociateFournisseur(CDEFourL $CDEFourL): bool {
		if ($CDEFourL->id_fournisseur === null)
			throw new UnexpectedValueException('The demande does not have a fournisseur');

		$CDEFourL->Fournisseur = ModelFournisseur::GetInstance()->GetById($CDEFourL->id_fournisseur);

		if ($CDEFourL->Fournisseur === null)
			throw new Exception('The fournisseur does not exist');

		return true;
	}

	public function AssociateState(CDEFourL $CDEFourL): bool {
		if ($CDEFourL->id_etat === null)
			throw new UnexpectedValueException('The demande does not have a state');

		$CDEFourL->Etat = ModelEtatModel::GetInstance()->GetById($CDEFourL->id_etat);
		
		if ($CDEFourL->Etat === null)
			throw new Exception('The state does not exist');

		return true;
	}
}
