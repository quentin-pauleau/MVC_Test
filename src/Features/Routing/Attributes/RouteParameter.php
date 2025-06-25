<?php
namespace Feature\Routing\Attributes;

use Attribute;
use Feature\Routing\Enums\RouteParameterSources;

#[Attribute(Attribute::TARGET_METHOD)]
final class RouteParameter
{
	public string $name;
	public RouteParameterSources $source;
	public string $param;

	/**
	 * If true the parameter is required, if false it is optional
	 * @var bool
	 */
	public bool $required = true;
}