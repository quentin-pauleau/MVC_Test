<?php
namespace Models\Entities;

use Models\Entities\Entity;
use Models\EntityLists\ListConversationMessageNotif;
use Models\ModelConversationMessage;
use Models\ModelConversationParticipant;
use Traits\CreatedAt;
use Traits\UniqueId;
use Core\UUID;


/**
 * A conversation message
 * 
 * model : {@see ModelConversationMessage}
 * 
 * list : {@see ListConversationMessage}
 * 
 * relations : <br>
 * 	- {@see self::Thread} -> {@see ConversationThread}
 * 	- {@see self::Author} -> {@see User}
 * 
 * traits : <br>
 * 	- {@see UniqueId}
 * 	- {@see CreatedAt}
 */
class ConversationMessage extends Entity
{
	use UniqueId;
	use CreatedAt;

	private const EXTRAIT_MAX_SIZE = 15;

	/**
	 * The id of the thread
	 * @var int|null
	 */
	public ?int $id_thread = null;

	/**
	 * The thread
	 * @var ConversationThread|null
	 */
	public ?ConversationThread $Thread = null;

	/**
	 * The id of the author
	 * @var int|null
	 */
	public ?int $id_author = null;

	/**
	 * The author
	 * @var ConversationParticipant|null
	 */
	public ?ConversationParticipant $Author = null;


	public ListConversationMessageNotif $Notifications;


	/**
	 * Content of the message
	 * @var string|null
	 */
	public ?string $content = null;

	public bool $is_important = false;
	
	public function __construct(
		?int $id_thread = null,
		?int $id_author = null,
		?string $content = null,
		bool $is_important = false,
		?ListConversationMessageNotif $Notifications = null,
		?UUID $UUID = null
	) {
		$this->id_thread = $id_thread;
		$this->id_author = $id_author;
		$this->content = $content;
		$this->is_important = $is_important;
		$this->Notifications = $Notifications ?? new ListConversationMessageNotif;
		$this->UUID = $UUID ?? new UUID();
	}


	public function GetExtrait(): string {
		if (strlen($this->content) > self::EXTRAIT_MAX_SIZE)
			return substr($this->content, 0, self::EXTRAIT_MAX_SIZE).'...';

		return $this->content;
	}


	public function IsUserNotified(int $id_user): bool {
		if ($this->Author === null)
			throw new \Exception('The message dont have an author');

		if ($this->Author->id_user === $id_user) // if the user is the author he cant be notified
			return false;

		foreach ($this->Notifications as $Notification)
			if (!$Notification->is_read && $Notification->id_participant === $id_user)
				return true;

		return false;
	}
}