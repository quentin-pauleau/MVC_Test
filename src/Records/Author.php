<?php
namespace Src\Records;

use DateTime;
use Modules\ORM\Binding\BindField;
use Modules\ORM\Binding\BindForeignRecord;
use Modules\ORM\Binding\BindPrimaryField;
use Modules\ORM\Binding\BindTable;
use Modules\ORM\Enums\DatabaseTypes;
use Modules\ORM\RecordManager\DatabaseRecord;

use Modules\Serialisation\Serialisable;
use Modules\Serialisation\SerialisableRecord;
use Modules\UUID\UuidV7;

#[BindTable('authors')]
final class Author
{
	use DatabaseRecord;
	use SerialisableRecord;


	#[BindPrimaryField]
	public UuidV7 $id;


	#[BindField]
	#[Serialisable]
	public string $firstName;

	#[BindField]
	#[Serialisable]
	public string $lastName;

	#[BindField(type: DatabaseTypes::DATETIME)]
	#[Serialisable]
	public DateTime $birthDate;

	#[BindField(type: DatabaseTypes::TEXT)]
	#[Serialisable]
	public string $bio = '';


	#[BindField(type: DatabaseTypes::URL)]
	#[Serialisable]
	public ?string $profilePictureUrl;


	#[BindForeignRecord(targetRecord: Book::class, targetRecordFieldName: 'authorId')]
	#[Serialisable]
	public array $books;



	public function getFullName(): string
	{
		return "{$this->firstName} {$this->lastName}";
	}
}