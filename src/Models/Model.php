<?php
namespace Models;

use Models\EntityLists\ListEntity;
use Models\Entities\Entity;
use Core\Database\Database;

abstract class Model
{
	protected Database $Database;

	protected function __construct() {
		$this->Database = new Database;
	}

	/**
	 * Instanciate a new {@see Entity} using data fetched from the database
	 * @param array $data data fetched from the database
	 * @return Entity the resulting instance
	 */
	abstract public function NewObject(array $data): ?Entity;


	/**
	 * Instanciate a new {@see ListEntity} using data fetched from the database
	 * @param array $data data fetched from the database
	 * @return ListEntity the resulting instance
	 */
	abstract public function NewList(array $data): ListEntity;


	/**
	 * Get an instance of the class from the databese by a given id 
	 * @param int $id
	 * @return ?Entity
	 */
	abstract public function GetById(int $id): ?Entity;

	/**
	 * Get a list of all instance of the class from the database
	 * @return ListEntity
	 */
	abstract public function GetAll(): ListEntity;

	/**
	 * Get if an instance of the class with the given id exist in the database
	 * @param int $id
	 * @return void
	 */
	abstract public function Any(int $id): bool;

	/**
	 * Insert an 
	 * @return bool true if the insertion was a success else false
	 */
	abstract public function Insert(): bool;

	/**
	 * Summary of update
	 * @return bool true if the insertion was a success else false
	 */
	abstract public function Update(): bool;

	abstract public function Delete(): bool;

	/**
	 * Summary of erase
	 * @param int $id
	 * @return bool true if the insertion was a success else false
	 */
	abstract public function EraseById(int $id): bool;
}