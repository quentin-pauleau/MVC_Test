<?php
namespace Modules\Routing\Controller;

use Modules\Http\Responses\RedirectionResponse;
use Modules\Http\Responses\ViewResponse;


trait WebController
{
	private function RenderView(
		string $path,
		array $data = [],
	)
	{
		return new ViewResponse(
			$path,
			$data
		);
	}

	private function Redirect(
		string $url,
	)
	{
		return new RedirectionResponse(
			$url
		);
	}
}