<?php
namespace Services;

use Modules\DatabaseConnection\DatabaseConnection;
use Modules\Result\Result;
use Modules\UUID\Uuid;
use Src\Records\User;

/**
 * User CRUD service class.
 */
class UserService
{
	private static DatabaseConnection $connection;

	public function __construct()
	{
		self::$connection = DatabaseConnection::GetConnection();
	}

	/**
	 * @return Result<User[], string>
	 */
	public function ReadAll(): Result
	{
		return Result::FromCallable(
			fn (): array => User::FindAll()
		);
	}

	/**
	 * @param Uuid $id
	 * @return Result<User|null, string>
	 */
	public function ReadById(Uuid $id): Result
	{
		return Result::FromCallable(
			fn (): User => User::TryFind($id)
		);
	}

	/**
	 * @param User $User
	 * @return Result<true, string>
	 */
	public function Save(User $User): Result
	{
		return Result::FromCallable(
			fn (): bool => $User->Save()
		);
	}


	/**
	 * @param User $User
	 * @return Result<true, string>
	 */
	public function Delete(User $User): Result
	{
		return Result::FromCallable(
			fn (): bool => $User->Delete()
		);
	}

	/**
	 * @param Uuid $id
	 * @return Result<true, string>
	 */
	public function DeleteById(Uuid $id): Result
	{
		return Result::FromCallable(
			fn (): bool => User::Find($id)->Delete()
		);
	}
}