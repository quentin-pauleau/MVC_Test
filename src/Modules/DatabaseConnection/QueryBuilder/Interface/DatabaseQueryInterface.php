<?php
namespace Feature\DatabaseQueryBuilder\Interface;

use Stringable;

interface DatabaseQueryInterface extends Stringable
{
	public function __tostring(): string;
}