<?php
namespace Src\Controllers;

use Modules\Http\Responses\ActionRedirectionResponse;
use Modules\Http\Responses\HTMLResponse;
use Modules\Http\Responses\Response;
use Modules\Http\Responses\URIRedirectionResponse;
use Modules\Routing\Controller\Controller;
use Modules\Routing\Route\Get;
use Modules\Routing\Route\Post;
use Modules\Routing\Route\Patch;
use Modules\Routing\Route\Delete;
use Services\BookService;
use Src\Records\Book;

#[Controller('book')]
final class BookController
{
	#[Get('/')]
	public function index(): Response {
		$result = (new BookService)->ReadAll();

		if ($result->IsFailure())
			return new HTMLResponse(
				'', 
				[
					'error' => 'Impossible to find the books',
					'book' => null
				]
			);

		$books = $result->GetResult();

		return new HTMLResponse(
			'',
			[
				"books" => $books
			]
		);
	}


	#[Get('/{id}')]
	public function show(int $id): Response {
		$result = (new BookService)->ReadAll();

		if ($result->IsFailure())
			return new HTMLResponse(
				'', 
				[
					'error' => 'Impossible to find the book',
					'book' => null
				]
			);

		$book = $result->GetResult();

		
		return new HTMLResponse(
			'',
			[
				"book" => $book
			]
		);
	}


	#[Post('/')]
	public function new(): Response {
		$book = new Book();

		$book->title = "Lord of the donut";

		$book->save();

		return new URIRedirectionResponse(
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
		$book = Book::TryFind($id);

		if ($book === null)
			throw new \Exception("Book not found");

		$book->Delete();

		return new URIRedirectionResponse(
			"/book"
		);
	}
}