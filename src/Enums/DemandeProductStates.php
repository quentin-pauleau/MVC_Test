<?php
namespace Enums;

use Traits\StaticClass;


class DemandeProductStates
{
	use StaticClass;
	
	/**
	 * The id for the default state of a product
	 * @var int
	 */
	public const DEFAULT = self::NON_TRAITE;

	/**
	 * The id for the default state of a product
	 * @var int
	 */
	public const NON_TRAITE = 9;
	
	/**
	 * The id for the default state of a product
	 * @var int
	 */
	public const RECEPTIONNE = 5;
	
	/**
	 * The id for the default state of a product
	 * @var int
	 */
	public const INDISPONIBLE = 7;
	
	/**
	 * The id for the default state of a product
	 * @var int
	 */
	public const TRAITE = 8;
}