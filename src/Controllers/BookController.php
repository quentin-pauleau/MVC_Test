<?php
namespace Src\Controllers;

use Modules\Http\Responses\Response;
use Modules\Http\Responses\RedirectionResponse;
use Modules\Http\Responses\ViewResponse;
use Modules\Routing\Controller\Controller;
use Modules\Routing\Param\BodyParam;
use Modules\Routing\Route\Get;
use Modules\Routing\Route\Post;
use Modules\Routing\Route\Patch;
use Modules\Routing\Route\Delete;
use Modules\Serialisation\Json;
use Services\BookService;
use Src\Records\Book;

#[Controller('book')]
final class BookController
{
	#[Get('/')]
	public function index(): Response {
		$result = (new BookService)->ReadAll();

		if ($result->IsFailure())
			return new ViewResponse(
				'', 
				[
					'error' => 'Impossible to find the books',
					'book' => null
				]
			);

		$books = $result->GetResult();

		return new ViewResponse(
			'',
			[
				"books" => $books
			]
		);
	}


	#[Get('/{id}')]
	public function show(int $id): Response {
		$result = (new BookService)->ReadById($id);

		if ($result->IsFailure())
			return new ViewResponse(
				'/book/show',
				[
					'error' => 'Impossible to find the book',
					'book' => null
				]
			);

		$book = $result->GetResult();
		
		return new ViewResponse(
			'/book/show',
			[
				"book" => $book
			]
		);
	}


	#[Get('/new')]
	public function newPage(
		#[BodyParam('book')] ?Book $book = null,
	): Response {
		return new ViewResponse(
			'/book/new',
			[
				'error' => '',
				'book' => $book
			]
		);
	}


	#[Post('/')]
	#[Post('/new')]
	public function new(
		#[BodyParam('book')] Book $book
	): Response {
		$book->title = "Lord of the donut";

		$result = (new BookService)->Save($book);

		if ($result->IsFailure())
			return new RedirectionResponse(
				"/book/new",
				body: Json::Serialize([
					"book" => $book,
					"errors" => [
						$result->GetError(),
					]
				]),
			);

		return new RedirectionResponse(
			"/book/{$book->id}"
		);
	}


	#[Patch('/{id}')]
	public function edit(int $id): void {
		$book = Book::TryFind($id);

		if ($book === null)
			throw new \Exception("Book not found");

		$book->title = "Lord of the donut";

		$book->save();
	}


	#[Delete('/{id}')]
	public function delete(int $id): Response {
		$result = (new BookService)->DeleteById($id);

		if ($result->IsFailure())
			return new ViewResponse(
				'',
				[
					'error' => 'Impossible to delete the book',
					'book' => null
				]
			);

		return new RedirectionResponse(
			"/book"
		);
	}
}