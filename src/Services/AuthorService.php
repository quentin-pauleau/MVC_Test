<?php
namespace Services;

use Modules\DatabaseConnection\DatabaseConnection;
use Modules\Result\Result;
use Modules\UUID\Uuid;
use Src\Records\Author;

/**
 * Author CRUD service class.
 */
class AuthorService
{
	private static DatabaseConnection $connection;

	public function __construct()
	{
		self::$connection = DatabaseConnection::GetConnection();
	}

	/**
	 * @return Result<Author[], string>
	 */
	public function ReadAll(): Result
	{
		return Result::FromCallable(
			fn (): array => Author::FindAll()
		);
	}

	/**
	 * @param Uuid $id
	 * @return Result<Author|null, string>
	 */
	public function ReadById(Uuid $id): Result
	{
		return Result::FromCallable(
			fn (): Author => Author::TryFind($id)
		);
	}

	/**
	 * @param Author $Author
	 * @return Result<true, string>
	 */
	public function Save(Author $Author): Result
	{
		return Result::FromCallable(
			fn (): bool => $Author->Save()
		);
	}


	/**
	 * @param Author $Author
	 * @return Result<true, string>
	 */
	public function Delete(Author $Author): Result
	{
		return Result::FromCallable(
			fn (): bool => $Author->Delete()
		);
	}

	/**
	 * @param Uuid $id
	 * @return Result<true, string>
	 */
	public function DeleteById(Uuid $id): Result
	{
		return Result::FromCallable(
			fn (): bool => Author::Find($id)->Delete()
		);
	}
}