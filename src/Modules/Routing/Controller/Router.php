<?php
namespace Modules\Routing\Controller;


final class Router
{
	private ?self $instance = null;

	private function __construct()
	{

	}

	public function New(): self
	{
		return $instance ?? $instance = new self;

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
