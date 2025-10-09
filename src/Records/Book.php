<?php
namespace Src\Records;

use DateTime;
use Modules\ORM\Attributes\BindField;
use Modules\ORM\Attributes\BindTable;
use Modules\ORM\Binding\BindForeignField;
use Modules\ORM\Binding\BindOneRelation;
use Modules\ORM\Binding\BindPrimaryField;
use Modules\ORM\Enums\DatabaseTypes;
use Modules\ORM\RecordManager\DatabaseRecord;
use Modules\ORM\RecordManager\RecordManager;
use Modules\Serialisation\Serialisable;
use Modules\Serialisation\SerialisableRecord;
use Modules\UUID\Uuid;
use Modules\UUID\UUID_V7;
use Modules\UUID\UuidV7;

#[BindTable(name: "books")]
class Book
{
	use DatabaseRecord;
	use SerialisableRecord;

	#[BindPrimaryField(
		defaultValueGenerator: fn () => UuidV7::generate()
	)]
	#[Serialisable]
	public Uuid $id;

	#[BindField]
	#[Serialisable]
	public string $title;

	#[BindField(type: DatabaseTypes::TEXT)]
	#[Serialisable]
	public string $description = '';

	#[BindForeignField(
		foreignRecord: Author::class,
		foreignRecordField: 'id',
		foreignRecordAlias: 'author',
	)]
	#[Serialisable]
	public Uuid $authorId;

	#[BindOneRelation(foreignBindName: 'authorId')]
	#[Serialisable]
	public Author $author;

	#[BindField]
	#[Serialisable]
	public DateTime $publicationDate;

	#[BindField]
	#[Serialisable]
	public int $pages;

	#[BindField]
	#[Serialisable]
	public float $price;
}