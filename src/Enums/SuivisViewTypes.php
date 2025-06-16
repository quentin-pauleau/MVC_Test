<?php
namespace Enums;

use Traits\StaticClass;

class SuivisViewTypes
{
	use StaticClass;

	/**
	 * Administration Only view type
	 * For view that can only be accessed by the admin of the app
	 * @var int
	 */
	public const ADMIN = -1;

	/**
	 * Default view type
	 * For view that can be accessed by anyone
	 * @var int
	 */
	public const DETAILS = 0;

	/**
	 * Demandeur Only view type
	 * For view that can only be accessed by the author of the demand
	 * @var int
	 */
	public const DEMANDEUR = 1;

	/**
	 * Destinataire Only view type
	 * For view that can only be accessed by the receiver of the demand
	 * @var int
	 */
	public const DESTINATAIRE = 2;

	/**
	 * Demandeur Or Destinataire view type
	 * For view that can only be accessed by the author and reciever of the demand
	 * @var int
	 */
	public const CONCERNED = 3;
}