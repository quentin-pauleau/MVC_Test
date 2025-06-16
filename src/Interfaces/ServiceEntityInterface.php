<?php
namespace Interfaces;

use Models\Entities\Entity;
use Models\EntityLists\ListEntity;

interface ServiceEntityInterface
{
	public function Find(int $id): Entity;

	public function FindAll(): ListEntity;

	public function Any(int $id): bool;
}