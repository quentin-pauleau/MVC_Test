<?php
namespace Modules\Routing\Controller;


use Modules\Http\Responses\JsonResponse;

trait JsonApiController
{
	protected function Ok(object|array $json): JsonResponse {
		return JsonResponse::Ok($json);
	}
	protected function Created(object|array $json): JsonResponse {
		return JsonResponse::Created($json);
	}
	protected function Accepted(object $json): JsonResponse {
		return JsonResponse::Accepted($json);
	}
	protected function NoContent(): JsonResponse {
		return JsonResponse::NoContent();
	}

	protected function BadRequest(string|array $errors = 'Bad Request'): JsonResponse {
		return JsonResponse::BadRequest($errors);
	}
	protected function Unauthorized(array|string $errors = 'Unauthorized'): JsonResponse {
		return JsonResponse::Unauthorized($errors);
	}
	protected function Forbidden(array|string $errors = 'Forbidden'): JsonResponse {
		return JsonResponse::Forbidden($errors);
	}
	protected function NotFound(array|string $errors = 'Not Found'): JsonResponse {
		return JsonResponse::NotFound($errors);
	}


	protected function InternalServerError(array|string $errors = 'Internal Server Error'): JsonResponse {
		return new JsonResponse($errors, statusCode: 500);
	}
	protected function NotImplemented(array|string $errors = 'Not Implemented'): JsonResponse {
		return new JsonResponse($errors, statusCode: 501);
	}

	protected function UnProcessableEntity(array|string $errors = 'Unprocessable Entity'): JsonResponse {
		return new JsonResponse(['error' => $errors], statusCode: 422);
	}
}