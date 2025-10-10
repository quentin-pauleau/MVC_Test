<?php
namespace Services;

use Modules\DatabaseConnection\DatabaseConnection;
use Modules\Result\Result;
use Modules\UUID\Uuid;
use Src\Records\Comment;

/**
 * Comment CRUD service class.
 */
class CommentService
{
	private static DatabaseConnection $connection;

	public function __construct()
	{
		self::$connection = DatabaseConnection::GetConnection();
	}

	/**
	 * @return Result<Comment[], string>
	 */
	public function ReadAll(): Result
	{
		return Result::FromCallable(
			fn (): array => Comment::FindAll()
		);
	}

	/**
	 * @param Uuid $id
	 * @return Result<Comment|null, string>
	 */
	public function ReadById(Uuid $id): Result
	{
		return Result::FromCallable(
			fn (): Comment => Comment::TryFind($id)
		);
	}


	public function ReadByUser(Uuid $userId): Result
	{
		return Result::FromCallable(
			fn (): array => Comment::FindMatch(['ownerId'])
		);
	}

	/**
	 * @param Comment $Comment
	 * @return Result<true, string>
	 */
	public function Save(Comment $Comment): Result
	{
		return Result::FromCallable(
			fn (): bool => $Comment->Save()
		);
	}


	/**
	 * @param Comment $Comment
	 * @return Result<true, string>
	 */
	public function Delete(Comment $Comment): Result
	{
		return Result::FromCallable(
			fn (): bool => $Comment->Delete()
		);
	}

	/**
	 * @param Uuid $id
	 * @return Result<true, string>
	 */
	public function DeleteById(Uuid $id): Result
	{
		return Result::FromCallable(
			fn (): bool => Comment::Find($id)->Delete()
		);
	}
}