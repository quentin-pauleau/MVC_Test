<?php
namespace Services\ServicesTypeLivraison;

use Exception;
use Interfaces\ServiceEntityInterface;
use Interfaces\ServiceInterface;
use Models\Entities\TypeLivraison;
use Models\EntityLists\ListTypeLivraison;
use Models\ModelTypeLivraison;

final class ServiceTypeLivraison implements ServiceInterface, ServiceEntityInterface
{
	public function __construct() {}

	public function Find(int $id): TypeLivraison {
		$State = ModelTypeLivraison::GetInstance()->GetById($id);

		if ($State === null)
			throw new Exception("TypeLivraison not found with \"$id\"");

		return $State;
	}

	public function FindAll(): ListTypeLivraison {
		return ModelTypeLivraison::GetInstance()->GetAll();
	}

	public function Any(int $id): bool {
		return ModelTypeLivraison::GetInstance()->Any($id);
	}
}