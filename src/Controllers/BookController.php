<?php
namespace Src\Controllers;

use Modules\Http\Responses\Response;
use Modules\Http\Responses\RedirectionResponse;
use Modules\Http\Responses\ViewResponse;
use Modules\Routing\Controller\Controller;
use Modules\Routing\Controller\WebController;
use Modules\Routing\Param\BodyParam;
use Modules\Routing\Param\UrlParam;
use Modules\Routing\Route\Get;
use Modules\Routing\Route\Post;
use Modules\Routing\Route\Patch;
use Modules\Routing\Route\Delete;
use Modules\Serialisation\Json;
use Modules\UUID\Uuid;
use Services\BookService;
use Src\Records\Book;

#[Controller('Book')]
final class BookController
{
	use WebController;

	#[Get]
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

		return $this->RenderView(
			'',
			[
				"books" => $books
			]
		);
	}


	#[Get('/{id}')]
	public function show(
		#[UrlParam] Uuid $id,
	): Response {
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
		
		return $this->RenderView(
			'/book/show',
			[
				"book" => $book
			]
		);
	}


	#[Get('/new')]
	public function newPage(
		#[BodyParam('book')] Book|null|false $book = null,
	): Response {
		if (!($book instanceof Book))
			$book = new Book;

		return $this->RenderView(
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
		#[BodyParam] Book|null $book,
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

		return $this->Redirect(
			"/book/{$book->id}"
		);
	}


	#[Patch('/{id}')]
	public function edit(
		#[UrlParam] int $id,
	): Response {
		$book = Book::TryFind($id);

		if ($book === null)
			throw new \Exception("Book not found");

		$book->title = "Lord of the donut";

		$book->save();

		return $this->Redirect(
			"/book/{$id}"
		);
	}


	#[Delete('/{id}')]
	public function delete(
		#[UrlParam] Uuid $id,
	): Response {
		$result = (new BookService)->DeleteById($id);

		if ($result->IsFailure())
			return $this->RenderView(
				'',
				[
					'error' => 'Impossible to delete the book',
					'book' => null
				]
			);

		return $this->Redirect(
			"/book"
		);
	}
}