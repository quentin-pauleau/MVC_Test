<?php
namespace Feature\DatabaseQueryBuilder\Interface;


interface DatabaseQueryInterface
{
	public function Build(): string;
}