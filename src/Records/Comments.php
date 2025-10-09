<?php
namespace Src\Records;

use Modules\ORM\Binding\BindField;
use Modules\ORM\Binding\BindTable;
use Modules\ORM\Binding\BindForeignField;
use Modules\ORM\Binding\BindOneRelation;
use Modules\ORM\Binding\BindPrimaryField;
use Modules\ORM\Enums\DatabaseTypes;
use Modules\ORM\RecordManager\DatabaseRecord;
use Modules\Serialisation\Serialisable;
use Modules\Serialisation\SerialisableRecord;
use Modules\UUID\UuidV7;

#[BindTable("comments")]
class Comment
{
	use DatabaseRecord;
	use SerialisableRecord;

	#[BindPrimaryField(
		defaultValueGenerator: fn () => UuidV7::generate(),
	)]
	#[Serialisable]
	public UuidV7 $id;

	#[BindField(type: DatabaseTypes::TEXT)]
	#[Serialisable]
	public string $text;

	#[BindField]
	#[Serialisable]
	public int $rating;

	#[BindForeignField(
		foreignRecord: User::class,
		foreignRecordField: "id",
		foreignRecordAlias: "owner",
	)]
	#[Serialisable]
	public int $ownerId;


	#[BindOneRelation("ownerId")]
	public User $owner;
}