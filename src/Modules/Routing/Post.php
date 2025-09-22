<?php
namespace Modules\Routing;



class Post extends Route
{
	public function __construct(
		public string $path,
	) {}
}
