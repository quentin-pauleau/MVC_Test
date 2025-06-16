<?php
namespace Feature\EntityToDatabase\Models;

use Feature\EntityToDatabase\Attributes\BindTable;


#[BindTable('')]
class ModelBase
{
	public static function GetById(int $id): self {
		return new self();
	}
}