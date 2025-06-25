<?php
namespace Feature\Routing\Attributes;

use Attribute;
use Core\Requests\RequestMethods;

#[Attribute]
class Route
{
	public string $path;


	/**
	 * By default all of them
	 * @var RequestMethods[]|null
	 */
	public array|null $methods = null;
}