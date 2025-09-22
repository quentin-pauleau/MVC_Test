<?php
namespace Modules\Routing;




class Get extends Route
{
	public function __construct(
		public string $path,
	) {}
}
