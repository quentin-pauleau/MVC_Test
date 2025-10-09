<?php
namespace Services;

use Modules\DatabaseConnection\DatabaseConnection;
use Modules\Result\Result;
use Modules\UUID\Uuid;
use Src\Records\Book;

/**
 * Book CRUD service class.
 */
class BookService
{
	private static DatabaseConnection $connection;

	public function __construct()
	{
		self::$connection = DatabaseConnection::GetConnection();
	}

	/**
	 * @return Result<Book[], string>
	 */
	public function ReadAll(): Result
	{
		return Result::FromCallable(
			fn (): array => Book::FindAll()
		);
	}

	/**
	 * @param int $id
	 * @return Result<Book|null, string>
	 */
	public function ReadById(int $id): Result
	{
		return Result::FromCallable(
			fn (): Book => Book::TryFind($id)
		);
	}

	/**
	 * @param Book $book
	 * @return Result<true, string>
	 */
	public function Save(Book $book): Result
	{
		return Result::FromCallable(
			fn (): bool => $book->Save()
		);
	}


	/**
	 * @param Book $book
	 * @return Result<true, string>
	 */
	public function Delete(Book $book): Result
	{
		return Result::FromCallable(
			fn (): bool => $book->Delete()
		);
	}

	/**
	 * @param Uuid $id
	 * @return Result<true, string>
	 */
	public function DeleteById(Uuid $id): Result
	{
		return Result::FromCallable(
			fn (): bool => Book::Find($id)->Delete()
		);
	}
}