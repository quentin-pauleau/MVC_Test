<?php
namespace Src\Records;

use Modules\ORM\Attributes\BindField;
use Modules\ORM\Attributes\BindTable;
use Modules\ORM\Attributes\KeyForeign;
use Modules\ORM\RecordManager\DatabaseRecord;
use Modules\Serialisation\Serialisable;
use Modules\UUID\UuidV7;

#[BindTable("comments")]
class Comments
{
	use DatabaseRecord;

	#[BindField]
	#[Serialisable]
	public UuidV7 $id;

	#[BindField]
	#[Serialisable]
	public string $text;

	#[BindField]
	#[Serialisable]
	public int $rating;

	#[BindField]
	#[KeyForeign()]
	#[Serialisable]
	public int $ownerId;


	public User $owner;
}