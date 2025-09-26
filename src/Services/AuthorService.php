<?php
namespace Services;

use Modules\DatabaseConnection\DatabaseConnection;
use Modules\Result\Result;
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
	 * Summary of ReadAll
	 * @return Result<Author[], string>
	 */
	public function ReadAll(): Result
	{
		return Result::FromCallable(
			fn (): array => Author::FindAll()
		);
	}

	/**
	 * @param int $id
	 * @return Result<Author|null, string>
	 */
	public function ReadById(int $id): Result
	{
		return Result::FromCallable(
			fn (): Author => Author::TryFind($id)
		);
	}

	/**
	 * @param \Src\Records\Author $Author
	 * @return Result<true, string>
	 */
	public function Save(Author $Author): Result
	{
		return Result::FromCallable(
			fn (): bool => $Author->Save()
		);
	}


	/**
	 * @param \Src\Records\Author $Author
	 * @return Result<true, string>
	 */
	public function Delete(Author $Author): Result
	{
		return Result::FromCallable(
			fn (): bool => $Author->Delete()
		);
	}

	/**
	 * @param int $id
	 * @return Result<true, string>
	 */
	public function DeleteById(int $id): Result
	{
		return Result::FromCallable(
			fn (): bool => Author::Find($id)->Delete()
		);
	}
}