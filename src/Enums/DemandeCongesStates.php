<?php
namespace Enums;

use Exception;
use Traits\StaticClass;



class DemandeCongesStates
{
	use StaticClass;

	public const DEFAULT = self::PENDING;

	/**
	 * Correspond to 'ETATAFF' = 'En attente' (pending)
	 * @var int
	 */
	public const PENDING = 0;

	/**
	 * Correspond to 'ETATAFF' = 'Acceptée' (accepted)
	 * @var int
	 */
	public const ACCEPTED = 1;

	/**
	 * Correspond to 'ETATAFF' = 'Refusée' (denied)
	 * @var int
	 */
	public const DENIED = 2;

	/**
	 * Correspond to 'ETATAFF' = 'Annulée' (canceled)
	 * @var int
	 */
	public const CANCELED = 3;


	/**
	 * Correspond to 'ETATAFF' = 'En attente' (pending) 
	 * but has not been treated by the 1st director
	 * @var int
	 */
	public const NOT_TREATED_1 = 11;


	/**
	 * Correspond to 'ETATAFF' = 'En attente' (pending) 
	 * but has not been treated by the 2nd director
	 * @var int
	 */
	public const NOT_TREATED_2 = 10;


	/**
	 * Correspond to 'ETATAFF' = 'En attente' (pending) 
	 * but has been treated by the 1st director
	 * @var int
	 */
	public const TREATED_1 = 21;

	/**
	 * Correspond to 'ETATAFF' = 'En attente' (pending) 
	 * but has been treated by the 2nd director
	 * @var int
	 */
	public const TREATED_2 = 22;

	
	public static function GetDescription(int $state): string {
		switch ($state) {
			case self::PENDING:
				return 'En attente';

			case self::ACCEPTED:
				return 'Acceptée';

			case self::DENIED:
				return 'Refusée';

			case self::CANCELED:
				return 'Annulée';

			
			case self::NOT_TREATED_1:
			case self::NOT_TREATED_2:
				return 'Non traité (En attente)';

			case self::TREATED_1:
			case self::TREATED_2:
				return 'Traité (En attente)';
			
			default:
				throw new Exception("The state \"$state\" does not exist");
		}
	}
}