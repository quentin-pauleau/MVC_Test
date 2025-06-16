<?php

namespace Models\Entities;

use Interfaces\ConversationSubjectInterface;
use Models\EntityLists\ListConversationMessage;
use Models\EntityLists\ListConversationParticipant;
use Models\Entities\Entity;

use DateTime;
use Traits\CreatedAt;


/**
 * A conversation thread
 * 
 * model : {@see ModelConversationThread}
 * list : {@see ListConversationThread}
 * 
 * relations :
 * 	- {@see self::Participants} -> {@see ListConversationParticipant}
 * 	- {@see self::Messages} -> {@see ListConversationParticipant}
 * 
 * traits :
 * 	- {@see CreatedAt}
 */
class ConversationThread extends Entity
{
	use CreatedAt;

	#region subject type 'enum'
	public const SUBJECT_TYPE_CDE_CLIENT = 'CDEClient';
	public const SUBJECT_TYPE_CDE_FOUR = 'CDEfour';
	public const SUBJECT_TYPE_DEMANDE_DIVERSE = 'demande_diverse';

	
	#endregion subject type 'enum'


	/**
	 * The title of the thread
	 * @var string
	 */
	public string $title;

	/**
	 * Status of the state (if false the thread is archived)
	 * @var bool
	 */
	public bool $is_active = true;
	
	public ListConversationParticipant $Participants;
	public ListConversationMessage $Messages;

	public ?ConversationSubjectInterface $ConversationSubject = null;

	public function __construct() {
		$this->Participants = new ListConversationParticipant();
		$this->Messages = new ListConversationMessage();
	}


	public function __tostring(): string {
		return $this->title;
	}


	public function GetDate(): ?DateTime {
		return ($this->ConversationSubject === null) ? $this->CreatedAt : $this->ConversationSubject->GetCreatedAt();
	}


	public function HasNotificationForUser(int $id_user): bool {
		if (!$this->IsParticipant($id_user))
			return false;

		foreach ($this->Messages as $Message)
			if ($Message->IsUserNotified($id_user))
				return true;

		return false;
	}


	public function IsParticipant(int $userId): bool {
		foreach($this->Participants as $Participant)
			if ($Participant->id_user === $userId)
				return true;
		
		return false;
	}
}