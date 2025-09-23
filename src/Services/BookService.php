<?php
namespace Services;

use Modules\DatabaseConnection\DatabaseConnection;
use Modules\Result\Result;
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
	 * Summary of ReadAll
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
	 * @return Result<Book, string>
	 */
	public function ReadById(int $id): Result
	{
		return Result::FromCallable(
			fn (): Book => Book::TryFind($id)
		);
	}

	/**
	 * @param \Src\Records\Book $book
	 * @return Result<true, string>
	 */
	public function Save(Book $book): Result
	{
		return Result::FromCallable(
			fn (): bool => $book->Save()
		);
	}


	/**
	 * @param \Src\Records\Book $book
	 * @return Result<true, string>
	 */
	public function Delete(Book $book): Result
	{
		return Result::FromCallable(
			fn (): bool => $book->Delete()
		);
	}

	/**
	 * @param int $id
	 * @return Result<true, string>
	 */
	public function DeleteById(int $id): Result
	{
		return Result::FromCallable(
			fn (): bool => Book::Find($id)->Delete()
		);
	}
}