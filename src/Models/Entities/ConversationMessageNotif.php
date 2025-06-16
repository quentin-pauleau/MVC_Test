<?php
namespace Models\Entities;

use DateTime;
use Models\Entities\Entity;


/**
 * A conversation Message Notification
 * 
 * model : {@see ModelConversationMessageNotif}
 * 
 * list : {@see ListConversationMessageNotif}
 * 
 * relations : <br>
 * 	- {@see self::Message} -> {@see ConversationMessage}
 * 	- {@see self::Participant} -> {@see ConversationParticipant}
 */
class ConversationMessageNotif extends Entity
{
	/**
	 * The id of the message
	 * @var int|null
	 */
	public ?int $id_message = null;

	/**
	 * The message
	 * @var ConversationMessage|null
	 */
	public ?ConversationMessage $Message = null;


	/**
	 * The id of the notified participant
	 * @var int|null
	 */
	public ?int $id_participant = null;

	/**
	 * The notified participant
	 * @var ConversationParticipant|null
	 */
	public ?ConversationParticipant $Participant = null;

	public bool $is_notified = false;
	
	public bool $is_read = false;
	public ?DateTime $ReadAt = null;

	
	public function __construct(
		?int $id_message = null,
		?int $id_participant = null,
		bool $is_read = false,
		bool $is_notified = false,
		?DateTime $ReadAt = null
	) {
		$this->id_message = $id_message;
		$this->id_participant = $id_participant;
		$this->is_read = $is_read;
		$this->is_notified = $is_notified;
		$this->ReadAt = $ReadAt;
	}

	public function SetReadAtFromString(string $ReadAt): void
	{
		$this->ReadAt = DateTime::createFromFormat('Y-m-d H:i:s', $ReadAt);
	}
}