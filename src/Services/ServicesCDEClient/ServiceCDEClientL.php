<?php
namespace Services\ServicesCDEClient;

use Exception;

use Interfaces\ServiceInterface;

use InvalidArgumentException;
use Models\Entities\CDEClientL;

use Models\ModelCDEClientH;
use Models\ModelCDEClientL;
use Models\ModelDegresUrgence;
use Models\ModelEtatModel;
use Models\ModelFournisseur;
use Services\ServicesState\ServiceState;
use Core\Session\ErrorHelper;


/**
 * 
 */
class ServiceCDEClientL implements ServiceInterface
{
	public function __construct() { }

	public function Find(int $id): CDEClientL {
		$Product = ModelCDEClientL::GetInstance()->GetById($id);

		if ($Product === null)
			throw new Exception("Product not found with \"id = $id\"");

		return $Product;
	}


	public function Any(int $id): bool {
		return ModelCDEClientL::GetInstance()->Any($id);
	}


	/**
	 * @param CDEClientL $Products
	 */
	public function SetState(int $idState, CDEClientL $Product): bool {
		$State = (new ServiceState)->Find($idState);
		$Product->id_etat = $idState;

		if (!ModelCDEClientL::GetInstance()->Update($Product))
			throw new Exception;
		
		$Product->Etat = $State;

		return true;
	}
	
	public function AssociateAllReferences(CDEClientL $CDEClientL): bool {
		try
		{
			if ($CDEClientL->id_fournisseur != null)
				$CDEClientL->Fournisseur = ModelFournisseur::GetInstance()->GetById($CDEClientL->id_fournisseur);

			if ($CDEClientL->id_etat != null)
				$CDEClientL->Etat = ModelEtatModel::GetInstance()->GetById($CDEClientL->id_etat);

			if ($CDEClientL->id_cde != null)
				$CDEClientL->CDEClientH = ModelCDEClientH::GetInstance()->GetById($CDEClientL->id_cde);

			if ($CDEClientL->id_urgence != null)
				$CDEClientL->Urgence = ModelDegresUrgence::GetInstance()->GetById($CDEClientL->id_urgence);
		}
		catch (Exception $e)
		{
			ErrorHelper::Set("connection", 'Une erreur est survenue lors de la récupération des informations d\'un produit de la commande/devis client');
			ErrorHelper::Set("connection", $e->getMessage());
			return false;
		}

		return true;
	}

	public function AssociateFournisseur(CDEClientL $CDEClientL): bool
	{
		if ($CDEClientL->id_fournisseur === null)
			throw new InvalidArgumentException('The product does not have a fournisseur');

		$CDEClientL->Fournisseur = ModelFournisseur::GetInstance()->GetById($CDEClientL->id_fournisseur);

		if ($CDEClientL->Fournisseur === null)
			throw new InvalidArgumentException('Fournisseur not found');

		return true;
	}

	public function AssociateState(CDEClientL $CDEClientL): bool
	{
		if ($CDEClientL->id_etat === null)
			throw new InvalidArgumentException('The product does not have a state');

		$CDEClientL->Etat = ModelEtatModel::GetInstance()->GetById($CDEClientL->id_etat);

		if ($CDEClientL->Etat === null)
			throw new InvalidArgumentException('State not found');

		return true;
	}

	public function AssociateCDEClientH(CDEClientL $CDEClientL): bool
	{
		if ($CDEClientL->id_cde === null)
			throw new InvalidArgumentException('The product does not have a demand');

		$CDEClientL->CDEClientH = (new ServiceCDEClientH)->Find($CDEClientL->id_cde);

		return true;
	}

	public function AssociateUrgence(CDEClientL $CDEClientL): bool
	{
		if ($CDEClientL->id_urgence === null)
			throw new InvalidArgumentException('The product does not have a degres d\'urgence');

		$CDEClientL->Urgence = ModelDegresUrgence::GetInstance()->GetById($CDEClientL->id_urgence);

		if ($CDEClientL->Urgence === null)
			throw new InvalidArgumentException('Degres d\'Urgence not found');

		return true;
	}
}
