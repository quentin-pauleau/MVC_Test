<?php
namespace Traits;
use Core\UUID;

/**
 * Represent a object with a UUID, this uuid can be used to join file with data inside the 'GED'
 */
trait UniqueId
{
	public UUID $UUID;

	public function __construct() {
		$this->UUID = new UUID();
	}
}