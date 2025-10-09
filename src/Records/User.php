<?php
namespace Src\Records;

use DateTime;
use Modules\ORM\Binding\BindField;
use Modules\ORM\Binding\BindManyRelation;
use Modules\ORM\Binding\BindPrimaryField;
use Modules\ORM\Binding\BindTable;
use Modules\ORM\Enums\DatabaseTypes;
use Modules\ORM\RecordManager\DatabaseRecord;
use Modules\Serialisation\Serialisable;
use Modules\Serialisation\SerialisableRecord;
use Modules\UUID\UuidV7;

#[BindTable('users')]
class User
{
	use DatabaseRecord;
	use SerialisableRecord;

	#[BindPrimaryField(
		defaultValueGenerator: fn() => UuidV7::generate(),
	)]
	#[Serialisable]
	public UuidV7 $id;

	#[BindField(type: 'varchar(50)')]
	#[Serialisable]
	public string $username;

	#[BindField(type: 'varchar(50)')]
	#[Serialisable]
	public string $password;

	#[BindField(type: DatabaseTypes::EMAIL)]
	#[Serialisable]
	public string $email;

	#[BindField]
	#[Serialisable]
	public DateTime $createdAt;

	#[BindField]
	#[Serialisable]
	public string $profileDescription;

	#[BindField]
	#[Serialisable]
	public string $profilePicture;


	#[BindManyRelation(target: Comment::class, targetField: 'id')]
	public array $Comments;
}