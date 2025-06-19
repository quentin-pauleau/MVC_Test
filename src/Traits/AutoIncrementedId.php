<?php
namespace Traits;

/**
 * Trait for entities with auto-incremented IDs
 * @property int|null $id the ID of the entity, null if the entity is not saved yet
 */
trait AutoIncrementedId
{
	public ?int $id = null;

	public function GetId(): ?int
	{
		return $this->id;
	}

	public function SetId(int $id): void
	{
		$this->id = $id;
	}
}