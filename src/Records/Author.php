<?php
namespace Src\Records;

use DateTime;
use Modules\ORM\Attributes\BindField;
use Modules\ORM\Attributes\BindTable;
use Modules\ORM\RecordManager\DatabaseRecord;
use Modules\ORM\RecordManager\RecordManager;


#[BindTable('authors')]
final class Author
{
	use DatabaseRecord;

	public static function GetManager(): RecordManager
	{
		return new RecordManager(self::class);
	}


	#[BindField('first_name')]
	public string $firstName;

	#[BindField('last_name')]
	public string $lastName;


	#[BindField(
		'birthDate',
		'date',
	)]
	public DateTime $birthDate;

	#[BindField(
		'bio',
		'text',
	)]
	public string $bio = '';

	#[BindField('bio',)]
	public ?string $pictureUrl;

	
	public array $books;



	public function getFullName(): string
	{
		return "{$this->firstName} {$this->lastName}";
	}
}