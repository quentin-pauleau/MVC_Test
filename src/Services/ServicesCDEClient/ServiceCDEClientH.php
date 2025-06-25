<?php
namespace Services\ServicesCDEClient;

use Enums\DemandeProductStates;
use Enums\DemandeStates;
use Exception;
use Interfaces\ServiceInterface;
use InvalidArgumentException;
use Models\Entities\CDEClientH;
use Models\Entities\ConversationParticipant;
use Models\ModelCDEClientH;
use Models\ModelCDEClientL;
use Models\ModelConversationThread;
use Models\ModelDegresUrgence;
use Models\ModelEtatModel;
use Models\ModelTypeLivraison;
use Models\ModelUser;
use Services\ServicesConversation\ServiceConversationParticipant;
use Services\ServicesState\ServiceState;
use Services\ServicesUser\ServiceUser;
use Core\Session\ErrorHelper;


class ServiceCDEClientH implements ServiceInterface
{
	public function __construct() { }


	/**
	 * Find a demand client by its ID
	 * @param int $id
	 * @throws \Exception
	 * @return CDEClientH
	 */
	public function Find(int $id): CDEClientH {
		$Demand = ModelCDEClientH::GetInstance()->GetById($id);

		if ($Demand === null)
			throw new Exception("Demand client not found with \"id = $id\"");

		return $Demand;
	}


	/**
	 * Verify if a demand client exists in the database
	 * @param int $id ID of the demand client
	 * @return bool true if the demand client exists, false otherwise
	 */
	public function Any(int $id): bool {
		return ModelCDEClientL::GetInstance()->Any($id);
	}


	public function New(CDEClientH $Demand): CDEClientH  {
		$Demand = clone $Demand; // avoid modifying the original object

		$Demand->id_etat = DemandeStates::DEFAULT;

		if (!ModelCDEClientH::GetInstance()->Insert($Demand))
			throw new Exception('Unable to insert the demand client');

		foreach ($Demand->Products as $Product) {
			$Product->CDEClientH_id = $Demand->id;
			$Product->id_etat = DemandeProductStates::DEFAULT;
		}

		if (!ModelCDEClientL::GetInstance()->InsertList($Demand->Products))
			throw new Exception('Unable to insert the products of the demand client');

		return $this->Find($Demand->id);
	}


	public function SetConversationThread(CDEClientH $CDEClientH, int $id_conversation_thread): CDEClientH {
		if ($CDEClientH->id === null)
			throw new Exception('The demand client is not inserted yet');

		$CDEClientH->id_conversation_thread = $id_conversation_thread;

		if (!ModelCDEClientH::GetInstance()->SetConversationThread($CDEClientH))
			throw new Exception('Unable to update the conversation thread of the demand client');

		return $this->Find($CDEClientH->id);
	}


	public function SetState(int $stateId, CDEClientH $Demand): bool {
		if ($Demand->id === null)
			throw new Exception('The demand client is not inserted yet');

		$State = (new ServiceState)->Find($stateId);

		$Demand->id_etat = $stateId;

		if (!ModelCDEClientH::GetInstance()->Update($Demand))
			throw new Exception('Unable to update the state of the demand client');

		$Demand->Etat = $State;

		return true;
	}


	public function SetDestinataire(int $destinataireId, CDEClientH $Demand): bool {
		if ($Demand->id === null)
			throw new Exception('The demand client is not inserted yet');

		$Destinataire = (new ServiceUser)->Find($destinataireId);
		$Demand->id_destinataire = $destinataireId;

		if (!ModelCDEClientH::GetInstance()->Update($Demand))
			throw new Exception('Unable to update the destinataire of the demand client');
		
		$Demand->Destinataire = $Destinataire;
		
		if ($Demand->ConversationThread !== null)
			(new ServiceConversationParticipant)->New(
				new ConversationParticipant(
					$Demand->id_conversation_thread,
					$Demand->id_destinataire
				)
			);
		
		return true;
	}


	#region Association Methods
	
	public function AssociateAllReferences(CDEClientH $CDEClientH): bool {
		try
		{
			//* One to One relations
			if ($CDEClientH->id_demandeur != null)
				$this->AssociateDemandeur($CDEClientH);

			if ($CDEClientH->id_destinataire != null)
				$this->AssociateDestinataire($CDEClientH);

			if ($CDEClientH->id_urgence != null)
				$this->AssociateUrgence($CDEClientH);

			if ($CDEClientH->id_type_livraison != null)
				$this->AssociateTypeLivraison($CDEClientH);

			if ($CDEClientH->id_etat != null)
				$this->AssociateState($CDEClientH);

			if ($CDEClientH->id_conversation_thread != null)
				$this->AssociateConversationThread($CDEClientH);
		}
		catch (Exception $e) {
			ErrorHelper::Set("connection", 'Une erreur est survenue lors de la récupération des informations '.$CDEClientH->GetTypeSentence());
			ErrorHelper::Set("connection", $e);
			return false;
		}

		return true;
	}

	public function AssociateDemandeur(CDEClientH $CDEClientH): bool {
		if ($CDEClientH->id_demandeur === null)
			throw new InvalidArgumentException('The demand does not have a demandeur');

		$CDEClientH->Demandeur = ModelUser::GetInstance()->GetById($CDEClientH->id_demandeur);

		if ($CDEClientH->Demandeur === null)
			throw new Exception('Demandeur not found');

		return true;
	}

	public function AssociateDestinataire(CDEClientH $CDEClientH): bool {
		if ($CDEClientH->id_destinataire === null)
			throw new InvalidArgumentException('The demand does not have a destinataire');

		$CDEClientH->Destinataire = ModelUser::GetInstance()->GetById($CDEClientH->id_destinataire);

		if ($CDEClientH->Destinataire === null)
			throw new Exception('Destinataire not found');

		return true;
	}

	public function AssociateUrgence(CDEClientH $CDEClientH): bool {
		if ($CDEClientH->id_urgence === null)
			throw new InvalidArgumentException('The demand does not have a "Degres d\'Urgence"');

		$CDEClientH->Urgence = ModelDegresUrgence::GetInstance()->GetById($CDEClientH->id_urgence);

		if ($CDEClientH->Urgence === null)
			throw new Exception('Degres d\'Urgence not found');

		return true;
	}

	public function AssociateTypeLivraison(CDEClientH $CDEClientH): bool {
		if ($CDEClientH->id_type_livraison === null)
			throw new InvalidArgumentException('The demand does not have a "type de livraison"');

		$CDEClientH->TypeLivraison = ModelTypeLivraison::GetInstance()->GetById($CDEClientH->id_type_livraison);

		if ($CDEClientH->TypeLivraison === null)
			throw new Exception('Type de Livraison not found');

		return true;
	}


	public function AssociateState(CDEClientH $CDEClientH): bool {
		if ($CDEClientH->id_etat === null)
			throw new InvalidArgumentException('The demand does not have a state');

		$CDEClientH->Etat = ModelEtatModel::GetInstance()->GetById($CDEClientH->id_etat);

		if ($CDEClientH->Etat === null)
			throw new Exception('State not found');

		return true;
	}


	public function AssociateConversationThread(CDEClientH $CDEClientH): bool {
		if ($CDEClientH->id_conversation_thread === null)
			throw new InvalidArgumentException('The demand does not have a conversation thread');

		$CDEClientH->ConversationThread = ModelConversationThread::GetInstance()->GetById($CDEClientH->id_conversation_thread);

		if ($CDEClientH->ConversationThread === null)
			throw new Exception('Conversation Thread not found');

		return true;
	}

	public function AssociateProduits(CDEClientH $CDEClientH): bool {
		if ($CDEClientH->id == null)
			throw new Exception('The demand isnt inserted yet');

		$CDEClientH->Products = ModelCDEClientL::GetInstance()->GetByCDEClientH($CDEClientH->id);

		return true;
	}

	#endregion Association Methods
}