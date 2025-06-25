<?php
namespace Models\Entities;

// use Models\EntityLists\ListCDEFourH;
// use Models\ModelCDEFourH;

use DateTime;
use Enums\DemandeStates;
use Models\EntityLists\ListCDEFourL;
use Models\Entities\Entity;
use Models\Entities\EtatModel;
use Models\Entities\Fournisseur;
use Models\Entities\User;

use Interfaces\ConversationSubjectInterface;

use Traits\CreatedAt;
use Traits\UniqueId;
use Core\UUID;

/**
 * Cette classe représente une commande fournisseur / commande stock
 * 
 * model : {@see ModelCDEFourH}
 * list : {@see ListCDEFourH}
 * 
 * 
 * trait : 
 * 	- {@see CreatedAt}
 * 	- {@see ConversationSubject}
 * 	- {@see UniqueId}
 */
class CDEFourH extends Entity implements ConversationSubjectInterface
{
	use CreatedAt;
	use UniqueId;

	public ?int $id_etat = null;
	public EtatModel $Etat;
	public ?int $id_fournisseur = null;
	public Fournisseur $Fournisseur;
	public ?int $id_destinataire = null;
	public User $Destinataire;
	public ?int $id_demandeur = null;
	public User $Demandeur;

	public string $demandeurComment = '';
	public string $destinataireComment = '';

	// public string $destinataireComment2;
	// public string $demandeurComment2;

	public ListCDEFourL $ListCDEFourL;

	public ?int $id_conversation_thread = null;
	public ?ConversationThread $ConversationThread = null;
	
	public function __construct(
		?int $id_fournisseur = null,
		?int $id_destinataire = null,
		?int $id_demandeur = null,
		?int $id_etat = null,
		?ListCDEFourL $ListCDEFourL = null,
		?UUID $UUID = null
	) {
		$this->id_fournisseur = $id_fournisseur;
		$this->id_destinataire = $id_destinataire;
		$this->id_demandeur = $id_demandeur;
		$this->id_etat = $id_etat;
		$this->ListCDEFourL = $ListCDEFourL ?? new ListCDEFourL;
		$this->UUID = $UUID ?? new UUID();
	}

	public function __tostring() {
		return "Demande fournisseur $this->Fournisseur";
	}


	public static function GetConversationSubjecttype(): string
	{
		return ConversationThread::SUBJECT_TYPE_CDE_FOUR;
	}

	public function GetConversationThread(): ?ConversationThread
	{
		return $this->ConversationThread;
	}

	public function GetConversationThreadId(): ?int
	{
		return $this->id_conversation_thread;
	}

	public function GetCreatedAt(): ?DateTime
	{
		return $this->CreatedAt;
	}

	public function GetId(): ?int
	{
		return $this->id ?? null;
	}

	public function IsClosed(): bool
	{
		return $this->id_etat === DemandeStates::CLOSED;
	}
}


