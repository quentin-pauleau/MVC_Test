<?php
namespace Feature\Services\Interfaces;

use Models\Entities\Entity;
use Models\EntityLists\ListEntity;

interface ServiceEntityInterface
{
	/**
	 * Find the entity with the given id
	 * @param int $id
	 * @return Entity the entity with the given id
	 */
	public function Find(int $id): Entity;

	/**
	 * Try finding the entity with the given id
	 * @param int $id
	 * @return Entity|null the entity with the given id or null if not found
	 */
	public function TryFind(int $id): Entity|null;

	/**
	 * Find all entity in the database
	 * @return ListEntity list of all entities
	 */
	public function FindAll(): ListEntity;

	/**
	 * Find if an entity exists with the given id
	 * @param int $id
	 * @return bool true if the entity exists, false otherwise
	 */
	public function Any(int $id): bool;

	/**
	 * Create a new entity in the database based on the given entity
	 * @param \Models\Entities\Entity $entity
	 * @return void
	 */
	public function New(Entity $entity);

	/**
	 * Saves changes made to an existing entity in the database.
	 * If the entity is not found, it will be created.
	 * 
	 * @param \Models\Entities\Entity $entity
	 * @return void
	 */
	public function Save(Entity $entity);

	/**
	 * Delete the given entity from the database
	 * @param \Models\Entities\Entity $entity
	 * @return void
	 */
	public function Delete(Entity $entity);
}