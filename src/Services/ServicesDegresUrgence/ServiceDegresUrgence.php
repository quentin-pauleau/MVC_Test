<?php
namespace Services\ServicesDegresUrgence;

use Exception;
use Interfaces\ServiceEntityInterface;
use Interfaces\ServiceInterface;
use Models\Entities\DegresUrgence;
use Models\EntityLists\ListDegresUrgence;
use Models\EntityLists\ListUser;
use Models\ModelDegresUrgence;

final class ServiceDegresUrgence implements ServiceInterface, ServiceEntityInterface
{
	public function __construct() {}

	public function Find(int $id): DegresUrgence {
		$State = ModelDegresUrgence::GetInstance()->GetById($id);

		if ($State === null)
			throw new Exception("'Degres d'urgence' not found with \"$id\"");

		return $State;
	}

	public function FindClient(): ListDegresUrgence {
		return ModelDegresUrgence::GetInstance()->GetAllClient();
	}

	public function FindFournisseur(): ListDegresUrgence {
		return ModelDegresUrgence::GetInstance()->GetAllFournisseur();
	}

	public function FindAll(): ListDegresUrgence {
		return ModelDegresUrgence::GetInstance()->GetAll();
	}

	public function Any(int $id): bool {
		return ModelDegresUrgence::GetInstance()->Any($id);
	}
}