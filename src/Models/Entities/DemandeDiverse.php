<?php
namespace Models\Entities;

use DateTime;
use Enums\DemandeStates;
use Models\Entities\Entity;

use Interfaces\ConversationSubjectInterface;

use Traits\CreatedAt;
use Traits\UniqueId;
use Core\UUID;


/**
 * Cette classe représente une demande diverse
 * 
 * model : {@see ModelDemandeDiverse}
 * list : {@see ListDemandeDiverse}
 * 
 * relations :
 * 	- {@see id_urgence} -> {@see DegresUrgence}
 * 
 * trait :
 * 	- {@see CreatedAt}
 * 	- {@see ConversationSubject}
 */
class DemandeDiverse extends Entity implements ConversationSubjectInterface
{
	use CreatedAt;
	use UniqueId;

	private const EXTRAIT_MAX_SIZE = 15;

	public ?int $id_urgence = null;
	public DegresUrgence $Urgence;

	public ?int $id_etat = null;
	public EtatModel $Etat;

	public ?int $id_destinataire = null;
	public User $Destinataire;

	public ?int $id_demandeur = null;
	public User $Demandeur;

	public ?int $id_conversation_thread = null;
	public ?ConversationThread $ConversationThread = null;


	/**
	 * Demande (Commentaire) du demandeur
	 * @var string
	 */
	public string $demande = "";

	/**
	 * Commentaire du destinataire
	 * @var string
	 */
	public string $commentaire = "";

	
	public function __construct(
		string $demande = '',
		?int $id_destinataire = null,
		?int $id_demandeur = null,
		?int $id_etat = null,
		?int $id_urgence = null,
		?UUID $UUID = null
	) {
		$this->demande = $demande;
		$this->id_destinataire = $id_destinataire;
		$this->id_demandeur = $id_demandeur;
		$this->id_etat = $id_etat;
		$this->id_urgence = $id_urgence;
		$this->UUID = $UUID ?? new UUID();
	}

	public function GetExtrait(): string {
		if (strlen($this->demande) > self::EXTRAIT_MAX_SIZE) {
			return substr($this->demande, 0, self::EXTRAIT_MAX_SIZE).'...';
		}

		return $this->demande;
	}

	
	public static function GetConversationSubjecttype(): string
	{
		return ConversationThread::SUBJECT_TYPE_DEMANDE_DIVERSE;
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
		return $this->id;
	}

	public function IsClosed(): bool
	{
		return $this->id_etat === DemandeStates::CLOSED;
	}
}
