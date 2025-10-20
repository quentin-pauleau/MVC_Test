<?php
namespace Modules\Routing\Router;

use Exception;
use Modules\Http\Responses\Response;
use Modules\Routing\Route\Route;
use ReflectionAttribute;
use ReflectionIntersectionType;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionUnionType;

readonly class RouteInfos {
	public string $action;
	public string $path;
	public string $method;


	public function __construct(string $path, string $method) {
		$this->action = '';
		$this->path = $path;
		$this->path = $method;
	}


	/**
	 * @param \ReflectionMethod $method
	 * @return self[] all routes associated with the method
	 */
	public static function FromReflectionMethod(ReflectionMethod $reflectionMethod): array {
		$reflectionReturnType = $reflectionMethod->getReturnType()
			?? throw new Exception("Not return type defined for {$reflectionMethod->name}");
		
		$reflectionReturnType->allowsNull()
			&& throw new Exception('Return type cannot be null');

		switch (true)
		{
			case $reflectionReturnType instanceof ReflectionNamedType:
				if (
					array_search(
						Response::class,
						class_parents($reflectionReturnType->getName())
					) === false
				)
					throw new Exception("A route should return a Response");

				
				break;
			
			case $reflectionReturnType instanceof ReflectionUnionType:
				foreach ($reflectionReturnType->getTypes() as $type) {
					if ($type instanceof ReflectionIntersectionType)
						throw new Exception('A route cannot return an intersection of types');

					$type;
					if (
						$type instanceof ReflectionNamedType
						&& array_search(
							Response::class,
							class_parents($type->getName())
						) === false
					)
						throw new Exception("A route should return a Response");
				}

				break;

			case $reflectionReturnType instanceof ReflectionIntersectionType:
				throw new Exception('A route cannot return an intersection of types');

			default:
				throw new Exception('Unknown reflection type');
		}

		$attributes = $reflectionMethod->GetAttributes(Route::class, ReflectionAttribute::IS_INSTANCEOF);

		foreach($attributes as $attribute) {
			[$path] = $attribute->getArguments();

			$method = strtoupper($attribute->getName());

			yield new self(
				$path,
				$method
			);
		}
	}
}