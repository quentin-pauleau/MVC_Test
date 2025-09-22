<?php
namespace Src\Records;

use DateTime;
use Modules\ORM\Attributes\BindField;
use Modules\ORM\Attributes\BindTable;
use Modules\ORM\RecordManager\DatabaseRecord;
use Modules\ORM\RecordManager\RecordManager;


#[BindTable(name: "books")]
class Book // extends DatabaseRecord
{
	use DatabaseRecord;

	public static function GetManager(): RecordManager {
		return RecordManager::Get(self::class);
	}


	#[BindField(
		name: "title",
		type: "varchar",
	)]
	public string $title;


	#[BindField(
		name: "description",
		type: "text",
	)]
	public string $description = '';


	#[BindField(
		name: "author",
		type: "varchar"
	)]
	public string $author;


	#[BindField(
		name: "publication_date",
		type: "date"
	)]
	public DateTime $publicationDate;


	#[BindField(
		name: "price",
		type: "float"
	)]
	public float $price;
}