<?php
namespace Src\Controllers;

use Exception;
use Modules\DatabaseConnection\DatabaseConnection;
use Modules\Http\Responses\JsonResponse;
use Modules\Http\Responses\Response;
use Modules\Http\Responses\RouteRedirectionResponse;
use Modules\Http\Responses\HttpResponse;
use Modules\Routing\Controller\Controller;
use Modules\Routing\Route\Get;
use Modules\Routing\Route\Post;
use Modules\Routing\Route\Patch;
use Modules\Routing\Route\Delete;
use Services\BookService;
use Src\Records\Book;

#[Controller('api/book')]
final class BookController
{
	#[Get('/')]
	public function index(): Response {
		$result = (new BookService)->ReadAll();

		if ($result->IsFailure())
			return HttpResponse::InternalServerError();

		$books = $result->GetResult();

		return new JsonResponse(
			[
				'books' => $books,
			],
		);
	}


	#[Get('/{id}')]
	public function show(int $id): Response {
		$result = (new BookService)->ReadById($id);

		if ($result->IsFailure())
			return HttpResponse::InternalServerError($result->GetError());
		
		$book = $result->GetResult();

		if ($book === null)
			return HttpResponse::NotFound();

		return new JsonResponse(
			[
				'book' => $book,
			],
		);
	}


	#[Post('/')]
	public function new(

	): Response {
		$book = new Book;
		
		$book->title = "Lord of the donut";

		$result = (new BookService)->Save($book);

		if ($result->IsFailure())
			return HttpResponse::InternalServerError($result->GetError());

		return HttpResponse::Created(); 
	}


	#[Patch('/{id}')]
	public function edit(int $id): Response {
		$BookService = new BookService;

		$book = Book::Find($id);

		if (!$book->Exist())
			return HttpResponse::NotFound("Book not found with id = {$id}");

		$result = $BookService->Save($book);

		if ($result->IsFailure())
			return HttpResponse::InternalServerError($result->GetError());

		return HttpResponse::Ok();
	}


	#[Delete('/{id}')]
	public function delete(int $id): Response {
		$book = Book::TryFind($id);

		if (!$book)
			return HttpResponse::NotFound();

		$result = (new BookService)->Delete($book);

		if ($result->IsFailure())
			return HttpResponse::InternalServerError($result->GetError());
		
		return HttpResponse::Ok();
	}
}