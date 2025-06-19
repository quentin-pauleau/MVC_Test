<?php
namespace Feature\EntityToDatabase\EntityManager;

use Traits\AutoIncrementedId;

/**
 * Class Entity
 *
 * @package Feature\Entity
 */
abstract class Entity
{
	use AutoIncrementedId;

	abstract public function __construct();
}