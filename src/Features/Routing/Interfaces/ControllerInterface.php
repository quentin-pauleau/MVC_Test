<?php
namespace Feature\Routing\Interfaces;


interface ControllerInterface
{
	/**
	 * 
	 * @param mixed $action
	 * @return string|int|null the returned value is used as the action of the route, return $action if you want to use it as-is or null to stop the routing process.
	 */
	public function Route(string|int $action): string|int|null;
}