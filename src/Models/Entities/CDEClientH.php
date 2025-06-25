<?php
namespace Models\Entities;

use DateTime;
use Enums\DemandeStates;
use Models\EntityLists\ListCDEClientL;
use Models\Entities\Entity;
use Models\Entities\User;
use Models\Entities\DegresUrgence;

use Interfaces\ConversationSubjectInterface;

use Traits\CreatedAt;
use Core\UUID;


/**
 * A conversation message
 * 
 * model : {@see ModelCDEClientL}
 * 
 * list : {@see ListCDEClientL}
 * 
 * 
 * relations : <br>
 * 	- {@see self::Destinataire} -> {@see User}
 * 	- {@see self::Demandeur} -> {@see User}
 * 	- {@see self::Urgence} -> {@see DegresUrgence}
 * 	- {@see self::Etat} -> {@see EtatModel}
 * 	- {@see self::TypeLivraison} -> {@see TypeLivraison}
 * 
 * trait : <br>
 * 	- {@see CreatedAt}
 * 	- {@see ConversationSubject}
 */
class CDEClientH extends Entity implements ConversationSubjectInterface
{
	use CreatedAt;

	public ?int $id_destinataire = null;
	public ?User $Destinataire = null;
	
	public ?int $id_demandeur = null;
	public ?User $Demandeur = null;

	public ?int $id_urgence = null;
	public ?DegresUrgence $Urgence = null;

	public ?int $id_etat = null;
	public ?EtatModel $Etat = null;

	public ?int $id_type_livraison = null;
	public ?TypeLivraison $TypeLivraison = null;

	public ?bool $cde = null;
	public ?bool $devis = null;
	public string $nom_client = "";
	// public string $ref_client;
	// public string $commentaire = "";
	public string $destinataireComment = '';
	public string $demandeurComment = '';
	public ?UUID $UUID = null;

	public ListCDEClientL $Products;

	public ?int $id_conversation_thread = null;
	public ?ConversationThread $ConversationThread = null;


	public function __construct(
		?int $id_destinataire = null,
		?int $id_demandeur = null,
		?int $id_urgence = null,
		?int $id_etat = null,
		?int $id_type_livraison = null,
		string $nom_client = '',
		?bool $cde = null,
		?bool $devis = null,
		string $destinataireComment = '',
		string $demandeurComment = '',
		?ListCDEClientL $Products = null,
		?UUID $UUID = null
	) {
		$this->id_destinataire = $id_destinataire;
		$this->id_demandeur = $id_demandeur;
		$this->id_urgence = $id_urgence;
		$this->id_etat = $id_etat;
		$this->id_type_livraison = $id_type_livraison;
		$this->nom_client = $nom_client;
		$this->cde = $cde;
		$this->devis = $devis;
		$this->destinataireComment = $destinataireComment;
		$this->demandeurComment = $demandeurComment;
		$this->Products = $Products ?? new ListCDEClientL;
		$this->UUID = $UUID ?? new UUID();
	}


	public function GetType(): string {
		if ($this->cde) {
			return "commande";
		}

		if ($this->devis) {
			return "devis";
		}
		
		return "demande";
	}

	public function GetTypeSentence(): string {
		if ($this->cde) {
			return "de la commande";
		}

		if ($this->devis) {
			return "du devis";
		}
		
		return "de la demande";
	}

	public static function GetConversationSubjecttype(): string
	{
		return ConversationThread::SUBJECT_TYPE_CDE_CLIENT;
	}

	public function GetConversationThread(): ?ConversationThread
	{
		return $this->ConversationThread ?? null;
	}

	public function GetConversationThreadId(): ?int
	{
		return $this->id_conversation_thread ?? null;
	}

	public function GetCreatedAt(): ?DateTime
	{
		return $this->CreatedAt;
	}

	public function GetId(): ?int
	{
		return $this->id;
	}


	public function IsClosed(): bool {
		return $this->id_etat === DemandeStates::CLOSED;
	}
}


