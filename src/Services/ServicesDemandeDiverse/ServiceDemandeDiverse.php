<?php
namespace Services\ServicesDemandeDiverse;

use Exception;
use Models\EntityLists\ListDemandeDiverse;
use Models\ModelConversationThread;
use Models\ModelDemandeDiverse;
use Models\ModelEtatModel;
use Models\ModelUser;
use Models\ModelDegresUrgence;
use Interfaces\ServiceInterface;
use Models\Entities\DemandeDiverse;
use Services\ServicesConversation\ServiceConversationThread;
use Services\ServicesState\ServiceState;
use Services\ServicesUser\ServiceUser;
use Utils\Requests\Request;
use Utils\Session\ErrorHelper;


class ServiceDemandeDiverse implements ServiceInterface
{
	public function __construct() { }


	public function Find(int $id): DemandeDiverse {
		$Demand = ModelDemandeDiverse::GetInstance()->GetById($id);

		if ($Demand === null)
			throw new Exception("Demand diverse not found with \"id = $id\"");

		return $Demand;
	}
	
	public function FindAll(): ListDemandeDiverse {
		return ModelDemandeDiverse::GetInstance()->GetAll();
	}

	public function Any(int $id): bool {
		return ModelDemandeDiverse::GetInstance()->Any($id);
	}


	public function New(DemandeDiverse $DemandeDiverse): DemandeDiverse {
		$DemandeDiverse = clone $DemandeDiverse; // avoid modifying the original object

		if (!ModelDemandeDiverse::GetInstance()->Insert($DemandeDiverse))
			throw new Exception('Unable to insert the demande diverse');

		return $this->Find($DemandeDiverse->id);
	}


	public function SetConversationThread(DemandeDiverse $DemandeDiverse, int $id_conversation_thread): DemandeDiverse {
		if ($DemandeDiverse->id === null)
			throw new Exception("Cannot set conversation thread for a demande diverse that has not been saved yet");

		if (!ModelConversationThread::GetInstance()->Any($id_conversation_thread))
			throw new Exception("Conversation thread not found with id = $id_conversation_thread");

		$DemandeDiverse->id_conversation_thread = $id_conversation_thread;

		if (!ModelDemandeDiverse::GetInstance()->Update($DemandeDiverse))
			throw new Exception('Unable to update the demande diverse with the conversation thread');

		return $this->Find($DemandeDiverse->id);
	}


	public function AssociateAllReferences(DemandeDiverse $DemandeDiverse): bool {
		try
		{
			if ($DemandeDiverse->id_urgence != null)
				$DemandeDiverse->Urgence = ModelDegresUrgence::GetInstance()->GetById($DemandeDiverse->id_urgence);

			if ($DemandeDiverse->id_demandeur != null)
				$DemandeDiverse->Demandeur = ModelUser::GetInstance()->GetById($DemandeDiverse->id_demandeur);

			if ($DemandeDiverse->id_destinataire != null)
				$DemandeDiverse->Destinataire = ModelUser::GetInstance()->GetById($DemandeDiverse->id_destinataire);

			if ($DemandeDiverse->id_conversation_thread != null)
				$DemandeDiverse->ConversationThread = ModelConversationThread::GetInstance()->GetById($DemandeDiverse->id_conversation_thread);

			if ($DemandeDiverse->id_etat != null)
				$DemandeDiverse->Etat = ModelEtatModel::GetInstance()->GetById($DemandeDiverse->id_etat);
		}
		catch (\Exception $e)
		{
			ErrorHelper::SetDefault("AssociateAllReferencesDemandeDiverse", 'Une erreur est survenue lors de la récupération des informations de la demande diverse');
			ErrorHelper::SetDebug("AssociateAllReferencesDemandeDiverse", $e->getMessage());
			return false;
		}

		return true;
	}


	public function AssociateUrgence(DemandeDiverse $DemandeDiverse): bool {
		if ($DemandeDiverse->id_urgence === null)
			throw new Exception('The demande does not have a "Degres d\'Urgence"');

		$DemandeDiverse->Urgence = ModelDegresUrgence::GetInstance()->GetById($DemandeDiverse->id_urgence);

		if ($DemandeDiverse->Urgence === null)
			throw new Exception("Degres d'Urgence not found with id = $DemandeDiverse->id_urgence");

		return true;
	}


	public function AssociateDemandeur(DemandeDiverse $DemandeDiverse): bool {
		if ($DemandeDiverse->id_demandeur === null)
			throw new Exception('The demande does not have a demandeur');

		$DemandeDiverse->Demandeur = (new ServiceUser)->Find($DemandeDiverse->id_demandeur);

		return true;
	}

	public function AssociateDestinataire(DemandeDiverse $DemandeDiverse): bool {
		if ($DemandeDiverse->id_destinataire === null)
			throw new Exception('The demande does not have a destinataire');

		$DemandeDiverse->Destinataire = (new ServiceUser)->Find($DemandeDiverse->id_destinataire);

		return true;
	}


	public function AssociateConversationThread(DemandeDiverse $DemandeDiverse): bool {
		if ($DemandeDiverse->id_conversation_thread === null)
			throw new Exception('The demande does not have a conversation thread');

		$DemandeDiverse->ConversationThread = (new ServiceConversationThread)->Find($DemandeDiverse->id_conversation_thread);

		return true;
	}

	public function AssociateEtat(DemandeDiverse $DemandeDiverse): bool {
		if ($DemandeDiverse->id_etat === null)
			throw new Exception('The demande does not have an etat');

		$DemandeDiverse->Etat = (new ServiceState)->Find($DemandeDiverse->id_etat);

		return true;
	}
}