<?php
namespace Services\ServicesCDEFour;

use Enums\DemandeProductStates;
use Enums\DemandeStates;
use Exception;

use Interfaces\ServiceInterface;

use Models\Entities\CDEClientH;
use Models\Entities\CDEFourH;
use Models\ModelCDEClientH;
use Models\ModelCDEFourH;
use Models\ModelCDEFourL;

use Models\ModelConversationThread;
use Models\ModelEtatModel;
use Models\ModelFournisseur;
use Models\ModelUser;

use Services\ServicesState\ServiceState;
use UnexpectedValueException;
use Core\Session\ErrorHelper;


/**
 * 
 */
class ServiceCDEFourH implements ServiceInterface
{
	public function __construct() { }

	
	public function Find(int $id): CDEFourH {
		$Order = ModelCDEFourH::GetInstance()->GetById($id);

		if ($Order === null)
			throw new Exception("Order (CDEFourH) not found with \"id = $id\"");

		return $Order;
	}


	public function Any(int $id): bool {
		return ModelCDEFourH::GetInstance()->Any($id);
	}


	public function New(CDEFourH $Demand): CDEFourH {
		$Demand = clone $Demand;
		$Demand->id_etat = DemandeStates::DEFAULT;

		if (!ModelCDEFourH::GetInstance()->Insert($Demand))
			throw new Exception('Unable to insert the demande');
		
		//* verify if the products are valid
		foreach ($Demand->ListCDEFourL as $CDEFourL) {
			//* Associate the product to the demande
			$CDEFourL->id_cde = $Demand->id;
			$CDEFourL->id_fournisseur = $Demand->id_fournisseur;
			$CDEFourL->id_etat = DemandeProductStates::DEFAULT;
		}

		if (!ModelCDEFourL::GetInstance()->InsertList($Demand->ListCDEFourL))
			throw new Exception('Unable to insert the products of the demand stock/fournisseur');

		return $this->Find($Demand->id);
	}


	public function SetConversationThread(CDEFourH $CDEFourH, int $id_conversation_thread): CDEFourH {
		if ($CDEFourH->id === null)
			throw new UnexpectedValueException('The demande does not inserted yet');

		if ($CDEFourH->id_conversation_thread !== null)
			throw new UnexpectedValueException('The demande already has a conversation thread');

		$CDEFourH->id_conversation_thread = $id_conversation_thread;

		if (!ModelCDEFourH::GetInstance()->Update($CDEFourH))
			throw new Exception('Unable to update the conversation thread of the demande');

		return $this->Find($CDEFourH->id);
	}


	public function AssociateAllReferences(CDEFourH $Order): bool {
		try
		{
			if ($Order->id_fournisseur != null)
				$this->AssociateFournisseur($Order);

			if ($Order->id_destinataire != null)
				$this->AssociateDestinataire($Order);

			if ($Order->id_demandeur != null)
				$this->AssociateDemandeur($Order);

			if ($Order->id_conversation_thread != null)
				$this->AssociateConversationThread($Order);

			if ($Order->id_etat != null)
				$this->AssociateState($Order);
		}
		catch (Exception $e)
		{
			ErrorHelper::SetDefault("AssociateAllReferences", 'Une erreur est survenue lors de la récupération des informations de la commande fournisseur');
			ErrorHelper::SetDebug("AssociateAllReferences", $e);
			return false;
		}

		return true;
	}


	public function AssociateFournisseur(CDEFourH $CDEFourH): bool {
		if ($CDEFourH->id_fournisseur === null)
			throw new UnexpectedValueException('The demande does not have a fournisseur');

		$CDEFourH->Fournisseur = ModelFournisseur::GetInstance()->GetById($CDEFourH->id_fournisseur);

		if ($CDEFourH->Fournisseur === null)
			throw new Exception('The fournisseur does not exist');
		
		return true;
	}


	public function AssociateDestinataire(CDEFourH $CDEFourH): bool {
		if ($CDEFourH->id_destinataire === null)
			throw new UnexpectedValueException('The demande does not have a destinataire');

		$CDEFourH->Destinataire = ModelUser::GetInstance()->GetById($CDEFourH->id_destinataire);
		
		if ($CDEFourH->Destinataire === null)
			throw new Exception('The destinataire does not exist');

		return true;
	}


	public function AssociateDemandeur(CDEFourH $CDEFourH): bool {
		if ($CDEFourH->id_demandeur === null)
			throw new UnexpectedValueException('The demande does not have a demandeur');

		$CDEFourH->Demandeur = ModelUser::GetInstance()->GetById($CDEFourH->id_demandeur);
		
		if ($CDEFourH->Demandeur === null)
			throw new Exception('The demandeur does not exist');

		return true;
	}


	public function AssociateConversationThread(CDEFourH $CDEFourH): bool {
		if ($CDEFourH->id_conversation_thread === null)
			throw new UnexpectedValueException('The demande does not have a conversation thread');
		
		$CDEFourH->ConversationThread = ModelConversationThread::GetInstance()->GetById($CDEFourH->id_conversation_thread);
		
		if ($CDEFourH->ConversationThread === null)
			throw new Exception('The conversation thread does not exist');

		return true;
	}


	public function AssociateState(CDEFourH $CDEFourH): bool {
		if ($CDEFourH->id_etat === null)
			throw new UnexpectedValueException('The demande does not have a state');
		
		$CDEFourH->Etat = (new ServiceState)->Find($CDEFourH->id_etat);

		if ($CDEFourH->Etat === null)
			throw new Exception('The etat does not exist');
		
		return true;
	}


	public function AssociateProduits(CDEFourH $CDEFourH): bool {
		if ($CDEFourH->id === null)
			throw new UnexpectedValueException('The demande does not inserted yet');

		$CDEFourH->ListCDEFourL = (new ServiceCDEFourL)->FindByDemand($CDEFourH->id);
		$CDEFourH->ListCDEFourL = ModelCDEFourL::GetInstance()->GetByCDEFourH($CDEFourH->id);
		
		return true;
	}
}
