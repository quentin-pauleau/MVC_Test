<?php
namespace Feature\Routing\Attributes;

use Attribute;
use Core\Requests\RequestMethods;


/**
 * Defined the request methods allowed to access this route
 */
#[Attribute(Attribute::TARGET_METHOD)]
final class RouteMethod
{
	/**
	 * Request methods allowed to access this route
	 * @var RequestMethods[]
	 */
	public array $requestMethods;
}