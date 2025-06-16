<?php
namespace Enums;

use Traits\StaticClass;


class DemandeStates
{
	use StaticClass;

	/**
	 * The id for the default state of an order
	 * @var int
	 */
	public const DEFAULT = self::OPEN;
	
	/**
	 * The id for representing an open order
	 * 
	 * 'Non traité' in the database
	 * @var int
	 */
	public const OPEN = 6;
	
	/**
	 * The id for representing a order in progress
	 * 
	 * 'En cours' in the database
	 * @var int
	 */
	public const IN_PROGRESS = 3;
	
	/**
	 * The id for representing an closed order
	 * 
	 * 'Cloturé' in the database
	 * @var int
	 */
	public const CLOSED = 4;
}