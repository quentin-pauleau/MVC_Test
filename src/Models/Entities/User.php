<?php
namespace Models\Entities;

use Models\Entities\Entity;


class User extends Entity
{
	/**
	 * Id of the administration account
	 * @var int
	 */
	public const ADMIN_ID = 1;

	/**
	 * Id of the 1st direction account
	 * @var int
	 */
	public const DIRECTOR_1_ID = 4;

	//! director 1 ID on the production server
	// public const DIRECTOR_1_ID = 7;


	/**
	 * Id of the 2st direction account
	 * @var int
	 */
	public const DIRECTOR_2_ID = 5;

	//! director 2 ID on the production server
	// public const DIRECTOR_2_ID = 6;


	public string $fname = '';
	public string $lname = '';

	/**
	 * Name use by the user to login
	 * @var string
	 */
	public string $login;

	/**
	 * Hashed password of the user
	 * @var string
	 */
	public string $pass;

	public function GetFullName(): string {
		// la base contient plusieurs fois le même nom pour fname et lname

		if ($this->IsAdmin())
			return 'Admin';

		if ($this->fname != "" && $this->fname == $this->lname)
			return $this->fname;

		return ($this->fname != "" || $this->lname != "") ? "$this->fname $this->lname" : "sans nom";
	}
	
	
	public function __tostring(): string {
		return $this->GetFullName();
	}

	public function IsAdmin(): bool {
		return $this->id == self::ADMIN_ID;
	}

	public function IsDirector(): bool {
		return $this->id == self::DIRECTOR_1_ID || $this->id == self::DIRECTOR_2_ID;
	}
}