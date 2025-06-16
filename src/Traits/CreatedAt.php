<?php

namespace Traits;

use DateTime;
use Utils\Database\Database;

/**
 * Represents an object that has a date defining when the object was created.
 */
trait CreatedAt
{
	public ?DateTime $CreatedAt = null;

	public function GetCreatedAtAsString(): ?string
	{
		return ($this->CreatedAt === null) ? null : $this->CreatedAt->format(Database::DATE_FORMAT);
	}

	public function SetCreatedAtFromString(string $CreatedAt): void
	{
		$this->CreatedAt = DateTime::createFromFormat(Database::DATE_FORMAT, $CreatedAt);
	}
}