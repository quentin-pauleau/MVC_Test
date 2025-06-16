<?php
namespace Services\ServicesFournisseur;

use Exception;
use Interfaces\ServiceEntityInterface;
use Interfaces\ServiceInterface;
use Models\Entities\Fournisseur;
use Models\EntityLists\ListFournisseur;
use Models\ModelFournisseur;

final class ServiceFournisseur implements ServiceInterface, ServiceEntityInterface
{
	public function __construct() {}

	public function Find(int $id): Fournisseur {
		$Fournisseur = ModelFournisseur::GetInstance()->GetById($id);

		if ($Fournisseur === null)
			throw new Exception("Fournisseur not found with \"$id\"");

		return $Fournisseur;
	}

	public function FindAll(): ListFournisseur {
		return ModelFournisseur::GetInstance()->GetAll();
	}

	public function Any(int $id): bool {
		return ModelFournisseur::GetInstance()->Any($id);
	}
}