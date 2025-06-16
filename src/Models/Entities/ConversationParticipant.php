<?php
namespace Models\Entities;

use Models\Entities\Entity;
use Traits\CreatedAt;

/**
 * A conversation thread
 * 
 * model : {@see ModelConversationParticipant}
 * list : {@see ListConversationParticipant}
 * 
 * 
 * relations :
 * 
 * 	- {@see self::Thread} -> {@see ConversationThread}
 * 	- {@see self::User} -> {@see User}
 * 	- {@see self::State} -> {@see ConversationParticipantState}
 * 
 * 
 * traits :
 * 	- {@see CreatedAt}
 */
class ConversationParticipant extends Entity
{
	use CreatedAt;

	public ?int $id_thread = null;
	public ?ConversationThread $Thread = null;

	public ?int $id_user = null;
	public ?User $User = null;

	public ?int $id_state = null;
	// public ?ConversationParticipantState $State = null;

	public bool $is_active = true;


	public function __construct(
		?int $id_thread = null,
		?int $id_user = null,
		?int $id_state = null,
		bool $is_active = true
	) {
		$this->id_thread= $id_thread;
		$this->id_user = $id_user;
		$this->id_state = $id_state;
		$this->is_active = $is_active;
	}

	public function __tostring(): string {
		return $this->User ?? 'Indéfini';
	}
}