<?php
namespace Modules\Routing;

use Exception;
use Modules\DotEnv\DotEnv;
use Modules\ExpendedReflection\ClassReflection;
use Modules\Routing\Controller\Controller;
use ReflectionClass;

final class Router
{
	private self|null $instance = null;
	private array $controllers = [];

	private function __construct()
	{

	}

	public static function getInstance(): self
	{
		return $instance ?? $instance = new self;
	}

	public function getControllers(): array
	{
		is_dir('app/controllers')
			?: throw new Exception("The controller directory does not exist. Please change the path in the setting file.");
		
		$controllers = glob('app/controllers/*.php');

		if ($controllers === [])
			throw new Exception("No controllers found.");

		foreach($controllers as &$controller) {
			require_once $controller;
			$controller = basename($controller, '.php');

			if (!class_exists($controller))
				continue;

			$reflectionClass = new ReflectionClass($controller);

			$reflectionControllerAttribute = $reflectionClass->getAttributes(Controller::class)[0] ?? null;

			if ($reflectionControllerAttribute === null)
				continue;

			[$name, $prefix] = $reflectionControllerAttribute->getArguments();

			$this->controllers[] = [
				'name' => $name,
				'class' => $controller,
				'prefix' => $prefix,
			];
		}

		if ($this->controllers === [])
			throw new Exception("No controllers found.");
		
		return $this->controllers;
	}


	public function GetRoute(): callable
	{
		


		return fn() => 1;
	}

	private function Route()// : Response
	{
		return $this->GetRoute()();
	}
}
