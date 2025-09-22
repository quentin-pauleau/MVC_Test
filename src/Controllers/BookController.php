<?php
namespace Src\Controllers;

use Core\Responses\HTMLResponse;
use Core\Responses\Response;
use Core\Responses\RouteRedirectionResponse;
use Modules\Http\HttpResponse;
use Modules\Routing\Controller\Controller;
use Modules\Routing\Route\Get;
use Modules\Routing\Route\Post;
use Modules\Routing\Route\Patch;
use Modules\Routing\Route\Delete;
use Src\Records\Book;

#[Controller('book')]
final class BookController
{
	#[Get('/')]
	public function index(): Response {
		$books = Book::FindAll();

		return new HTMLResponse(
			'',
			[
				"books" => $books
			]
		);
	}


	#[Get('/{id}')]
	public function show(int $id): Response {
		$book = Book::TryFind($id);
		
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

		return new RouteRedirectionResponse("/book/{$book->id}");
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
	public function delete(int $id): void {
		$book = Book::TryFind($id);

		if ($book === null)
			throw new \Exception("Book not found");

		$book->Delete();
	}
}