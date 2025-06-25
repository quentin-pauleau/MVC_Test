<?php
namespace Core\Session;

use Models\Entities\User;
use Traits\Singleton;

class SessionUser
{
	use Singleton;

	protected ?User $RefUser = null;
	protected ?int $id = null;

	private function __construct() {
		$_SESSION['data'] = [];
		$this->values = $_SESSION['data'];
	}


	public function GetRefUser(): ?User {
		return $this->RefUser;
	}

	public function GetId(): ?int {
		return $this->id;
	}

	public function GetIsLoggedIn(): void {

	}
	
}