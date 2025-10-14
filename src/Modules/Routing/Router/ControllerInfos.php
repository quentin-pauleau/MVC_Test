<?php
namespace Modules\Routing\Router;

use Exception;
use Modules\Routing\Controller\Controller;
use Modules\Routing\Route\Route;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;

readonly class ControllerInfos {
	public string $name;
	public string $class;
	public string $prefix;
	public ReflectionClass $reflectionClass;


	public function __construct(
		string $class,
		string $name,
		string $prefix,
		?ReflectionClass $reflectionClass,
	) {
		$this->class = $class;
		$this->name = $name;
		$this->prefix = $prefix;
		$this->reflectionClass = $reflectionClass ?? new ReflectionClass($this->class);
	}


	public static function FromClassName(string $className): self {
		class_exists($className)
			?: throw new Exception("Class '$className' does not exist.");

		$reflectionClass = new ReflectionClass($className);
		
		$reflectionAttribute =  $reflectionClass->getAttributes(Controller::class)[0]
			?: throw new Exception("Class '$className' is not a controller.");

		$reflectionConstructor = $reflectionClass->getConstructor();

		if ($reflectionConstructor !== null && !$reflectionConstructor->isPublic())
			throw new Exception("Constructor of class '$className' is not public.");
		
		$reflectionClass->isInstantiable()
			?: throw new Exception("Class '$className' is not instantiable.");
		
		[$name, $prefix] = $reflectionAttribute->getArguments();

		return new self (
			$className,
			$name,
			$prefix,
			$reflectionClass
		);
	}


	public function GetRoutes(): array {
		$reflectionMethods = $this->reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

		$routes = [];

		foreach($reflectionMethods as $method) {
			
		}

		return $routes;
	}
}