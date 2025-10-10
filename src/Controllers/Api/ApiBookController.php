<?php
namespace Src\Controllers;


use Modules\Http\Responses\Response;
use Modules\Http\Responses\HttpResponse;
use Modules\Routing\Controller\Controller;
use Modules\Routing\Controller\JsonApiController;
use Modules\Routing\Param\BodyParam;
use Modules\Routing\Param\UrlParam;
use Modules\Routing\Route\Get;
use Modules\Routing\Route\Post;
use Modules\Routing\Route\Patch;
use Modules\Routing\Route\Delete;
use Modules\UUID\UUID;
use Modules\UUID\UuidV1;
use Modules\UUID\UuidV7;
use Services\BookService;
use Src\Records\Book;

#[Controller('BookApi', 'api/book')]
final class ApiBookController
{
	use JsonApiController;

	#[Get]
	public function index(): Response {
		$result = (new BookService)->ReadAll();

		if ($result->IsFailure())
			return $this->InternalServerError($result->GetError());

		$books = $result->GetResult();

		return $this->Ok($books);
	}


	#[Get('/{id}')]
	public function show(
		#[UrlParam("id")] Uuid $id
	): Response {
		$result = (new BookService)->ReadById($id);

		if ($result->IsFailure())
			return $this->InternalServerError($result->GetError());
		
		$book = $result->GetResult();

		if ($book === null)
			return HttpResponse::NotFound();

		return $this->Ok($book);
	}


	#[Post('/')]
	public function new(
		#[BodyParam("book")] Book|false $book
	): Response {
		if (!($book instanceof Book))
			return $this->UnProcessableEntity("Invalid book data.");
		
		$result = (new BookService)->Save($book);

		if ($result->IsFailure())
			return $this->InternalServerError($result->GetError());

		return $this->Created($book); 
	}


	#[Patch('/{id}')]
	public function edit(
		#[UrlParam("id")] UUID $id,
		#[BodyParam("book")] Book|false $book
	): Response {
		if (!($book instanceof Book))
			return $this->UnProcessableEntity("Invalid book data.");

		if (Book::Any($id))
			return $this->NotFound();

		$book->id = $id;

		$result = (new BookService)->Save($book);

		if ($result->IsFailure())
			return $this->InternalServerError($result->GetError());

		return $this->Ok($book);
	}


	#[Delete('/{id}')]
	public function delete(
		#[UrlParam("id")] UuidV7 $id
	): Response {
		if (!Book::Any($id))
			return $this->NotFound();

		$result = (new BookService)->DeleteById($id);

		if ($result->IsFailure())
			return $this->InternalServerError($result->GetError());
		
		return $this->NoContent();
	}
}