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
use Services\AuthorService;
use Src\Records\Author;

#[Controller('AuthorApi', 'api/author')]
final class ApiAuthorController
{
	use JsonApiController;

	#[Get]
	public function index(): Response {
		$result = (new AuthorService)->ReadAll();

		if ($result->IsFailure())
			return $this->InternalServerError($result->GetError());

		$authors = $result->GetResult();

		return $this->Ok($authors);
	}


	#[Get('/{id}')]
	public function show(
		#[BodyParam] int $id
	): Response {
		$result = (new AuthorService)->ReadById($id);

		if ($result->IsFailure())
			return $this->InternalServerError($result->GetError());
		
		$author = $result->GetResult();

		if ($author === null)
			return HttpResponse::NotFound();

		return $this->Ok($author);
	}


	#[Post]
	public function new(
		#[BodyParam] Author|false $author
	): Response {
		if (!($author instanceof Author))
			return $this->UnProcessableEntity("Invalid author data.");
		
		$result = (new AuthorService)->Save($author);

		if ($result->IsFailure())
			return $this->InternalServerError($result->GetError());

		return $this->Created($author); 
	}


	#[Patch('/{id}')]
	public function edit(
		#[UrlParam] int $id,
		#[BodyParam] Author|false $author
	): Response {
		if (!($author instanceof Author))
			return $this->UnProcessableEntity("Invalid author data.");

		if (Author::Any($id))
			return $this->NotFound();

		$author->id = $id;

		$result = (new AuthorService)->Save($author);

		if ($result->IsFailure())
			return $this->InternalServerError($result->GetError());

		return $this->Ok($author);
	}
	
	#[Delete('/{id}')]
	public function delete(
		#[UrlParam] int $id,
	): Response {
		if (!Author::Any($id))
			return $this->NotFound();

		$result = (new AuthorService)->DeleteById($id);

		if ($result->IsFailure())
			return $this->InternalServerError($result->GetError());
		
		return $this->NoContent();
	}
}