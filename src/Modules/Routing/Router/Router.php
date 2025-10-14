<?php
namespace Modules\Routing\Router;

use Exception;
use Modules\DotEnv\DotEnv;
use Modules\ExpendedReflection\ClassReflection;
use Modules\Routing\Controller\Controller;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;

final class Router
{
	private self|null $instance = null;
	private array $controllers = [];

	private function __construct() {}

	public static function getInstance(): self
	{
		return $instance ?? $instance = new self;
	}

	public function getControllers(): array
	{
		is_dir('app/controllers')
			?: throw new Exception("The controller directory does not exist. Please change the path in the setting file.");
		
		$controllerPaths = glob('app/controllers/*.php');

		if ($controllerPaths === [])
			throw new Exception("No controllers found.");

		foreach($controllerPaths as &$controllerPath) {
			require_once $controllerPath;
			$controllerClassName = basename($controllerPath, '.php');

			if (!class_exists($controllerClassName))
				continue; // maybe add a exception here?

			try
			{
				yield $this->controllers[] = ControllerInfos::FromClassName($controllerClassName);
			}
			catch(Exception $e)
			{
				// not a valid controller
				// TODO : add error handling
				continue;
			}
		}
	}


	public function FindRoute(string $url): callable
	{
		$url = trim($url);
		
		foreach($this->getControllers() as $controller) {
			$controller->FindRoute($url);
		}
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
