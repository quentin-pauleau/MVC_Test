<?php
namespace Services\ServicesConversation;

use \Exception;

use Models\Entities\ConversationParticipant;
use Models\ModelConversationMessageNotif;
use Models\ModelConversationParticipant;
use Models\ModelConversationThread;
use Models\ModelUser;

use Interfaces\ServiceInterface;

final class ServiceConversationParticipant implements ServiceInterface
{
	public function __construct() { }

	public function Find(int $id): ConversationParticipant {
		$Participant = ModelConversationParticipant::GetInstance()->GetById($id);

		if ($Participant === null)
			throw new Exception("Conversation participant not found with \"id = $id\"");

		return $Participant;
	}


	public function FindByUserAndThread(int $userId, int $threadId): ConversationParticipant {
		$Participant = ModelConversationParticipant::GetInstance()->GetByUserIdAndThreadId($userId, $threadId);

		if ($Participant === null)
			throw new Exception("No conversation participants found for thread with \"user id = $userId\" and \"thread id = $threadId\"");

		return $Participant;
	}


	public function FindAllByThreadId(int $threadId) {
		return ModelConversationParticipant::GetInstance()->GetByThreadId($threadId);
	}


	public function AnyByUserAndThread(int $userId, int $threadId): bool {
		$Participant = ModelConversationParticipant::GetInstance()->GetByUserIdAndThreadId($userId, $threadId);

		return $Participant !== null;
	}


	public function New(ConversationParticipant $Participant): ConversationParticipant {
		$Participant = clone $Participant;

		if ($Participant->id_thread === null || $Participant->id_user === null)
			throw new Exception('The conversation participant must have a thread and a user');

		if ($this->AnyByUserAndThread($Participant->id_user, $Participant->id_thread))
			return $this->FindByUserAndThread($Participant->id_user, $Participant->id_thread);

		if (!ModelConversationParticipant::GetInstance()->Insert($Participant))
			throw new Exception('Unable to insert the conversation participant');

		return $this->Find($Participant->id);
	}


	public function AssociateAllReferences(ConversationParticipant $Participant): bool {
		try
		{
			if ($Participant->id_thread != null)
				$this->AssociateConversationThread($Participant);

			if ($Participant->id_user != null)
				$this->AssociateUser($Participant);
		}
		catch (Exception $e)
		{
			//TODO : Error Handling
			return false;
		}

		return true;
	}


	public function AssociateConversationThread(ConversationParticipant $Participant): bool {
		if ($Participant->id_thread === null)
			throw new Exception('The conversation message does not have a conversation thread');

		$Participant->Thread = ModelConversationThread::GetInstance()->GetById($Participant->id_thread);

		if ($Participant->Thread === null)
			throw new Exception('Conversation thread not found');
		
		return true;
	}


	public function AssociateUser(ConversationParticipant $Participant): bool {
		if ($Participant->id_user === null)
			throw new Exception('The conversation message does not have an Author');

		$Participant->User = ModelUser::GetInstance()->GetById($Participant->id_user);

		if ($Participant->User === null)
			throw new Exception('Author not found');

		return true;
	}


	public function AssociateNotifications(ConversationParticipant $Participant): bool {
		if ($Participant->id === null)
			throw new Exception('The conversation message has not been inserted yet');

		$Participant->Notifications = ModelConversationMessageNotif::GetInstance()->GetByParticipantId($Participant->id);

		if ($Participant->Thread === null)
			throw new Exception('The conversation thread was not found');

		return true;
	}

}