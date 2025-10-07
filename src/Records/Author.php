<?php
namespace Src\Records;

use DateTime;
use Modules\ORM\Attributes\BindField;
use Modules\ORM\Attributes\BindTable;
use Modules\ORM\RecordManager\DatabaseRecord;
use Modules\ORM\RecordManager\RecordManager;
use Modules\Serialisation\Serialisable;


#[BindTable('authors')]
final class Author
{
	use DatabaseRecord;

	public static function GetManager(): RecordManager
	{
		return new RecordManager(self::class);
	}

	#[
		BindField('first_name'), 
		Serialisable
	]
	public string $firstName;

	#[
		BindField('last_name'), 
		Serialisable
	]
	public string $lastName;

	#[
		BindField('birthDate', 'text'),
		Serialisable
	]
	public DateTime $birthDate;

	#[
		BindField('bio', 'text',), 
		Serialisable
	]
	public string $bio = '';


	#[
		BindField('profilePictureUrl', 'varchar')
	]
	public ?string $profilePictureUrl;

	
	public array $books;



	public function getFullName(): string
	{
		return "{$this->firstName} {$this->lastName}";
	}
}