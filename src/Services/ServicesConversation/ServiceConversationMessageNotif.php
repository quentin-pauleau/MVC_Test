<?php
namespace Services\ServicesConversation;

use Exception;

use Models\Entities\ConversationMessage;
use Models\Entities\ConversationMessageNotif;
use Models\EntityLists\ListConversationMessageNotif;
use Models\ModelConversationMessage;
use Models\ModelConversationMessageNotif;
use Models\ModelConversationParticipant;


class ServiceConversationMessageNotif
{
	public function __construct() {}

	public function Find(int $id): ConversationMessageNotif {
		$Notif = ModelConversationMessageNotif::GetInstance()->GetById($id);

		if ($Notif === null)
			throw new Exception("Conversation message notification not found with \"id = $id\"");

		return $Notif;
	}


	public function FindByMessage(int $messageId): ListConversationMessageNotif {
		return ModelConversationMessageNotif::GetInstance()->GetByMessageId($messageId);
	}


	public function New(ConversationMessageNotif $Notif): ConversationMessageNotif {
		if (!$id = ModelConversationMessageNotif::GetInstance()->Insert($Notif))
			throw new Exception('Unable to insert the conversation message notification');

		return $this->Find($id);
	}


	public function ActualiseNotification(ConversationMessage $ConversationMessage, int $userId): bool {
		$Notif = ModelConversationMessageNotif::GetInstance()->GetNewByMessageIdAndUserId($ConversationMessage->id, $userId);

		if ($Notif === null)
			return false;

		$ConversationMessage->Notifications->Add($Notif);

		if (!ModelConversationMessageNotif::GetInstance()->SetToRead($Notif))
			throw new Exception("unable to set the notification to \"read\"");

		return true;
	}

	public function IsUserNotifiedByMessage(int $messageId, int $userId): bool
	{
		$Notif = ModelConversationMessageNotif::GetInstance()->GetNewByMessageIdAndUserId($messageId, $userId);

		if ($Notif !== null)
			return $Notif->is_read;
		
		$Message = ModelConversationMessage::GetInstance()->GetById($messageId);

		if ($Message === null)
			throw new Exception('The notification does not exist');

		$Message->Author = ModelConversationParticipant::GetInstance()->GetById($Message->id_author);

		if ($Message->Author->id_user === $userId)
			return true;

		return false;
	}
}