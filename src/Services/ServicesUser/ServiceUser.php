<?php
namespace Services\ServicesUser;

use Exception;
use Interfaces\ServiceEntityInterface;
use Interfaces\ServiceInterface;
use Models\Entities\User;
use Models\EntityLists\ListUser;
use Models\ModelEtatModel;
use Models\ModelUser;

final class ServiceUser implements ServiceInterface, ServiceEntityInterface
{
	public function __construct() {}

	public function Find(int $id): User {
		$State = ModelUser::GetInstance()->GetById($id);

		if ($State === null)
			throw new Exception("User not found with \"$id\"");

		return $State;
	}

	public function FindAll(): ListUser {
		return ModelUser::GetInstance()->GetAll();
	}

	public function Any(int $id): bool {
		return ModelUser::GetInstance()->Any($id);
	}
}