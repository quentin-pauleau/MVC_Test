<?php
namespace Services\ServicesState;

use Exception;
use Interfaces\ServiceEntityInterface;
use Interfaces\ServiceInterface;
use Models\Entities\EtatModel;
use Models\EntityLists\ListEtatModel;
use Models\ModelEtatModel;

final class ServiceState implements ServiceInterface, ServiceEntityInterface
{
	public function __construct() {}

	public function Find(int $id): EtatModel {
		$State = ModelEtatModel::GetInstance()->GetById($id);

		if ($State === null)
			throw new Exception("State not found with \"$id\"");

		return $State;
	}

	public function FindAll(): ListEtatModel {
		return ModelEtatModel::GetInstance()->GetAll();
	}

	public function Any(int $id): bool {
		return ModelEtatModel::GetInstance()->Any($id);
	}
}