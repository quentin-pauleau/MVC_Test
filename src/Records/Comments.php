<?php
namespace Src\Records;

use Modules\ORM\Attributes\BindTable;
use Modules\ORM\RecordManager\DatabaseRecord;

#[BindTable("comments")]
class Comments
{
	use DatabaseRecord;

	public string $text;
}