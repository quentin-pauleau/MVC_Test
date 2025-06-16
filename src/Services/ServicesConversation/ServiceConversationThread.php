<?php
namespace Services\ServicesConversation;

use \Exception;

use Models\ModelConversationMessage;
use Models\ModelConversationParticipant;
use Models\ModelConversationThread;
use Models\ModelCDEFourH;
use Models\ModelCDEClientH;
use Models\ModelDemandeDiverse;

use Models\Entities\ConversationThread;
use Models\Entities\ConversationMessage;
use Models\Entities\ConversationParticipant;

use Interfaces\ServiceInterface;
use Services\ServicesDemandeDiverse\ServiceDemandeDiverse;
use Services\ServicesCDEClient\ServiceCDEClientH;
use Services\ServicesCDEFour\ServiceCDEFourH;
use UnexpectedValueException;

final class ServiceConversationThread implements ServiceInterface
{
	public function __construct() { }

	public function Find(int $id): ConversationThread {
		$Thread = ModelConversationThread::GetInstance()->GetById($id);

		if ($Thread === null)
			throw new Exception("thread not found where \"id = $id\"");

		return $Thread;
	}


	public function New(ConversationThread $Thread): ConversationThread {
		if (!ModelConversationThread::GetInstance()->Insert($Thread))
			throw new Exception('Unable to insert the conversation thread');
		
		$ServiceConversationParticipant = new ServiceConversationParticipant;
		$ServiceConversationMessage = new ServiceConversationMessage;

		foreach ($Thread->Participants as $Participant) {
			$Participant->id_thread = $Thread->id;
			$ServiceConversationParticipant->New($Participant);
		}

		foreach ($Thread->Messages as $Message) {
			$Message->id_thread = $Thread->id;
			$ServiceConversationMessage->New($Message);
		}

		return $this->Find($Thread->id);
	}


	public function AssociateAllReferences(ConversationThread $Thread): bool {
		if ($Thread->id != null) {
			$this->AssociateMessages($Thread);
			$this->AssociateParticipants($Thread);
		}

		return true;
	}


	public function AssociateMessages(ConversationThread $Thread): bool {
		if ($Thread->id === null)
			throw new UnexpectedValueException('The thread isnt inserted yet');

		$Thread->Messages = ModelConversationMessage::GetInstance()->GetByThreadId($Thread->id);

		return true;
	}

	public function AssociateParticipants(ConversationThread $Thread): bool {
		if ($Thread->id === null)
			throw new UnexpectedValueException('The thread isnt inserted yet');

		$Thread->Participants = ModelConversationParticipant::GetInstance()->GetByThreadId($Thread->id);

		return true;
	}


	public function SetCDEClientHAsConversationSubject(ConversationThread $Thread, int $CDEClientHId): ConversationThread {
		$CDEClientH = (new ServiceCDEClientH)->Find($CDEClientHId);
		$ServiceConversationParticipant = new ServiceConversationParticipant;
		$ServiceConversationMessage = new ServiceConversationMessage;
		
		//* Update the conversation thread
		$Thread->title = "Demande client '$CDEClientH->nom_client'";
		
		ModelConversationThread::GetInstance()->Update($Thread);

		//* Add the participant "demandeur"
		// add the demandeur as the 1st participant
		$Demandeur = $ServiceConversationParticipant->New(
			new ConversationParticipant(
				$Thread->id,
				$CDEClientH->id_demandeur,
			)
		);

		// add the comment of the demandeur as the 1st message
		if ($CDEClientH->demandeurComment != '')
			$Thread->Messages->Add(
				$ServiceConversationMessage->New(
					new ConversationMessage(
						$Thread->id,
						$Demandeur->id,
						$CDEClientH->demandeurComment,
					)
				)
			);

		//* Add the participant "destinataire"
		// add the destinataire as the 2nd participant
		$Destinataire = $ServiceConversationParticipant->New(
			new ConversationParticipant(
				$Thread->id,
				$CDEClientH->id_destinataire,
			)
		);

		// add the comment of the destinataire as the 2nd message
		if ($CDEClientH->destinataireComment != '')
			$Thread->Messages->Add(
				$ServiceConversationMessage->New(
					new ConversationMessage(
						$Thread->id,
						$Destinataire->id,
						$CDEClientH->destinataireComment,
					)
				)
			);

		$Thread->Participants->Add(
			$Demandeur,
			$Destinataire,
		);

		//* Update the command client
		(new ServiceCDEClientH)->SetConversationThread(
			$CDEClientH, 
			$Thread->id
		);

		$Thread->ConversationSubject = $CDEClientH;
		return $Thread;
	}


	public function SetCDEFourHAsConversationSubject(ConversationThread $Thread, int $CDEFourHId): ConversationThread {
		$ServiceCDEFourH = new ServiceCDEFourH;
		$CDEFourH = $ServiceCDEFourH->Find($CDEFourHId);
		$ServiceCDEFourH->AssociateFournisseur($CDEFourH);

		$ServiceConversationParticipant = new ServiceConversationParticipant;
		$ServiceConversationMessage = new ServiceConversationMessage;
		
		//* Update the conversation thread
		$Thread->title = "Demande stock '$CDEFourH->Fournisseur'";

		ModelConversationThread::GetInstance()->Update($Thread);


		//* Add the participant "demandeur"
		// add the demandeur as the 1st participant

		$Demandeur = $ServiceConversationParticipant->New(
			new ConversationParticipant(
				$Thread->id,
				$CDEFourH->id_demandeur,
			),
		);

		if ($CDEFourH->demandeurComment != '')
			$Thread->Messages->Add(
				$ServiceConversationMessage->New(
					new ConversationMessage(
						$Thread->id,
						$Demandeur->id,
						$CDEFourH->demandeurComment,
					)
				)
			);
		
		//* Add the participant "destinataire"
		// add the destinataire as the 2nd participant

		$Destinataire = $ServiceConversationParticipant->New(
			new ConversationParticipant(
				$Thread->id,
				$CDEFourH->id_destinataire,
			)
		);

		// add the comment of the destinataire as the 2nd message
		if ($CDEFourH->destinataireComment != '')
			$Thread->Messages->Add(
				$ServiceConversationMessage->New(
					new ConversationMessage(
						$Thread->id,
						$Destinataire->id,
						$CDEFourH->destinataireComment,
					)
				)
			);
		
		$Thread->Participants->Add(
			$Demandeur,
			$Destinataire,
		);

		//* Update the command fournisseur/stock
		(new ServiceCDEFourH)->SetConversationThread(
			$CDEFourH, 
			$Thread->id
		);

		$Thread->ConversationSubject = $CDEFourH;
		return $Thread;
	}


	public function SetDemandeDiverseAsConversationSubject(ConversationThread $Thread, int $demandeDiverseId): ConversationThread {
		$ServiceDemandeDiverse = new ServiceDemandeDiverse;
		$DemandeDiverse = $ServiceDemandeDiverse->Find($demandeDiverseId);
		$ServiceDemandeDiverse->AssociateDemandeur($DemandeDiverse);
		
		$ServiceConversationParticipant = new ServiceConversationParticipant;
		$ServiceConversationMessage = new ServiceConversationMessage;

		//* Update the conversation thread
		$Thread->title = "Demande de $DemandeDiverse->Demandeur";

		ModelConversationThread::GetInstance()->Update($Thread);

		//* Add the participant "demandeur"
		// add the demandeur as the 1st participant
		$Demandeur = $ServiceConversationParticipant->New(new ConversationParticipant(
				$Thread->id,
				$DemandeDiverse->id_demandeur,
			),
		);
		
		// add the comment of the demandeur as the 1st message
		if ($DemandeDiverse->demande != '')
			$Thread->Messages->Add(
				$ServiceConversationMessage->New(
					new ConversationMessage(
						$Thread->id,
						$Demandeur->id,
						$DemandeDiverse->demande,
					)
				)
			);

		//* Add the participant "destinataire"
		// add the destinataire as the 2nd participant
		$Destinataire = $ServiceConversationParticipant->New(
			new ConversationParticipant(
				$Thread->id,
				$DemandeDiverse->id_destinataire,
			)
		);

		// add the comment of the destinataire as the 2nd message
		if ($DemandeDiverse->commentaire != '')
			$Thread->Messages->Add(
				$ServiceConversationMessage->New(
					new ConversationMessage(
						$Thread->id,
						$Destinataire->id,
						$DemandeDiverse->commentaire,
					)
				)
			);
		
		
		$Thread->Participants->Add(
			$Demandeur,
			$Destinataire,
		);

		//* Update the demande diverse
		$ServiceDemandeDiverse->SetConversationThread(
			$DemandeDiverse, 
			$Thread->id
		);

		$Thread->ConversationSubject = $DemandeDiverse;
		return $Thread;
	}


	public function AssociateConversationSubject(ConversationThread $Thread): bool {
		$Thread->ConversationSubject = (
			ModelCDEClientH::GetInstance()->GetByConversationThreadId($Thread->id)
			?? ModelCDEFourH::GetInstance()->GetByConversationThreadId($Thread->id)
			?? ModelDemandeDiverse::GetInstance()->GetByConversationThreadId($Thread->id)
		);
		
		return true;
	}
}