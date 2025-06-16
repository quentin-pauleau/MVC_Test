<?php
namespace Models\Entities;

use DateTime;

use Exception;
use Models\Entities\Entity;
use Models\Entities\User;
use Models\Entities\TypeConges;

use Enums\DemandeCongesStates;

use Traits\UniqueId;

/**
 * A 'conges' demand
 * 
 * model : {@see ModelDemandeConges}
 * list : {@see ListDemandeConges}
 * 
 * relations :
 * 	- {@see self:Demandeur} | {@see self::id_demandeur} -> {@see User}
 * 	- {@see self:TypeConges} | {@see self::id_type_conges} -> {@see TypeConges}
 * 
 * traits :
 * 	- {@see UniqueId}
 */
class DemandeConges extends Entity
{
	use UniqueId;

	public const UUID_FIELDNAME = 'F_DEMANDECP_UUID';

	/**
	 * The author of the demand (ID)
	 * @var int|null
	 */
	public ?int $id_demandeur = null;

	/**
	 * The author of the demand
	 * @var User
	 */
	public User $Demandeur;

	/**
	 * The comment of the demand
	 * @var string
	 */
	public string $comment = "";


	#region Date 
	/**
	 * The start date of the 'conges'
	 * @var DateTime|null
	 */
	public ?DateTime $StartDate = null;

	/**
	 * When true the Morning (of the Start Date) is included in the 'conges'
	 * else the 'conges' start at 12:00
	 * @var bool
	 */
	public bool $StartMorning = true;


	/**
	 * The end date of the 'conges'
	 * @var DateTime|null
	 */
	public ?DateTime $EndDate = null;

	/**
	 * When true the Afternoon (of the End Date) is included in the 'conges'
	 * else the 'conges' end at 12:00
	 * @var bool
	 */
	public bool $EndAfternoon = true;

	#endregion Date


	/**
	 * The type of the 'conges' (ID)
	 * @var int|null
	 */
	public ?int $id_type_conges = null;

	/**
	 * The type of the 'conges'
	 * @var 
	 */
	public TypeConges $TypeConges;


	/**
	 * The state of the demand ("En attente", "Acceptée", "Refusée")
	 * @var 
	 */
	public ?string $state = null;


	#region State Manipulation Properties 

	/**
	 * Validated by the 1st person
	 * @var bool
	 */
	public bool $is_validated1 = false;

	/**
	 * denied by the 1st person
	 * @var bool
	 */
	public bool $is_denied1 = false;

	/**
	 * accepted by the 1st person
	 * @var bool
	 */
	public bool $is_accepted1 = false;


	/**
	 * validated by the 2nd person
	 * @var bool
	 */
	public bool $is_validated2 = false;

	/**
	 * denied by the 2nd person
	 * @var bool
	 */
	public bool $is_denied2 = false;

	/**
	 * accepted by the 2nd person
	 * @var bool
	 */
	public bool $is_accepted2 = false;


	/**
	 * cancel by the 'demandeur'
	 * @var bool
	 */
	public bool $is_canceled = false;

	#endregion State Manipulation Properties


	public function __construct(
		?int $id_demandeur = null,
		?DateTime $StartDate = null,
		?DateTime $EndDate = null,
		?bool $StartMorning = null,
		?bool $EndAfternoon = null
	) {
		$this->id_demandeur = $id_demandeur;

		$this->StartDate = $StartDate;
		$this->EndDate = $EndDate;

		$this->StartMorning ??= $StartMorning;
		$this->EndAfternoon ??= $EndAfternoon;
	}

	public function GetStart(): string {
		if ($this->StartDate === null)
			return 'indéfini';

		if ($this->StartMorning)
			return $this->StartDate->format('d/m/Y').' Matin';

		return $this->StartDate->format('d/m/Y').' Après-Midi';
	}

	public function GetEnd(): string {
		if ($this->EndDate === null)
			return 'indéfini';

		if ($this->EndAfternoon)
			return $this->EndDate->format('d/m/Y').' Matin';

		return $this->EndDate->format('d/m/Y').' Après-Midi';
	}

	public function GetStateDescription(): string {
		if ($this->state === null)
			return 'indéfini';

		return DemandeCongesStates::GetDescription($this->state);
	}

	public function IsTreated(): string {
		if ($this->is_canceled)
			return true;

		return ($this->is_accepted1 || $this->is_denied1) && ($this->is_accepted2 || $this->is_denied2);
	}

	public function IsTreatedBy(int $userId): string {
		switch ($userId) {
			case User::DIRECTOR_1_ID:
				return $this->is_accepted1 || $this->is_denied1;

			case User::DIRECTOR_2_ID:
				return $this->is_accepted2 || $this->is_denied2;
		}

		throw new Exception("User with id = '$userId' is not a member of the direction");
	}
}
