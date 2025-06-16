<?php
namespace Services\ServicesConversation;

use \Exception;

use Models\Entities\ConversationMessageNotif;
use Models\Entities\ConversationParticipant;
use Models\EntityLists\ListConversationMessage;
use Models\EntityLists\ListConversationMessageNotif;
use Models\ModelConversationMessage;
use Models\ModelConversationMessageNotif;
use Models\ModelConversationParticipant;

use Models\Entities\ConversationMessage;

use Interfaces\ServiceInterface;

final class ServiceConversationMessage implements ServiceInterface
{
	public function __construct() { }

	public function Find(int $id): ConversationMessage {
		$Message = ModelConversationMessage::GetInstance()->GetById($id);

		if ($Message === null)
			throw new Exception("Conversation message not found with \"id = $id\"");

		return $Message;
	}


	public function FindByThread(int $threadId): ListConversationMessage {
		return ModelConversationMessage::GetInstance()->GetByThreadId($threadId);
	}

	public function FindNotifed(int $id): ListConversationMessage {
		$ListNotifications = ModelConversationMessageNotif::GetInstance()->GetNewByUserId($id);

		$Messages = new ListConversationMessage;
		
		foreach ($ListNotifications as $Notification)
			$Messages->Add($this->Find($Notification->id_message));

		return $Messages;
	}


	public function AssociateAllReferences(ConversationMessage $Message): bool {
		try
		{
			// if ($Message->id_thread  null)
			// 	$this->AssociateConversationThread($Message);

			if ($Message->id_author != null)
				$this->AssociateAuthor($Message);
		}
		catch (Exception $e)
		{
			//TODO : Error Handling
			return false;
		}

		return true;
	}


	public function AssociateConversationThread(ConversationMessage $Message): bool {
		if ($Message->id_thread === null)
			throw new Exception('The conversation message does not have a conversation thread');

		$Message->Thread = (new ServiceConversationThread)->Find($Message->id_thread);

		return true;
	}


	public function AssociateAuthor(ConversationMessage $Message): bool {
		if ($Message->id_author === null)
			throw new Exception('The conversation message does not have an Author');

		$Message->Author = (new ServiceConversationParticipant)->Find($Message->id_author);

		return true;
	}


	public function AssociateNotifications(ConversationMessage $Message): bool {
		if ($Message->id === null)
			throw new Exception('The conversation message has not been inserted yet');

		$Message->Notifications = (new ServiceConversationMessageNotif)->FindByMessage($Message->id);

		return true;
	}


	/**
	 * Inserts a new conversation message
	 * @param ConversationMessage $Message
	 * @return ConversationMessage the inserted version of the message
	 */
	public function New(ConversationMessage $Message): ConversationMessage {
		if ($Message->id_thread === null)
			throw new Exception('The conversation message does not have a thread');

		$Message = clone $Message;

		if (!ModelConversationMessage::GetInstance()->Insert($Message))
			throw new Exception('Unable to insert the conversation message');

		$ListParticipant = ModelConversationParticipant::GetInstance()->GetByThreadId($Message->id_thread);

		if ($ListParticipant->IsEmpty()) {
			ModelConversationParticipant::GetInstance()->Insert(
				new ConversationParticipant(
					$Message->id_thread,
					$Message->id_author,
				)
			);
		}
		else {
			$ListNotif = new ListConversationMessageNotif;
			
			foreach ($ListParticipant as $Participant) {
				if ($Participant->id === $Message->id_author)
					continue; // skip the author of the message

				$ListNotif->Add(
					new ConversationMessageNotif(
						$Message->id,
						$Participant->id,
					)
				);
			}

			if (!ModelConversationMessageNotif::GetInstance()->InsertList($ListNotif))
				throw new Exception("insertion of the conversation message notifications failed");
		}

		return $this->Find($Message->id);
	}

}