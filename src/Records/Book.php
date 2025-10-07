<?php
namespace Src\Records;

use DateTime;
use Modules\ORM\Attributes\BindField;
use Modules\ORM\Attributes\BindTable;
use Modules\ORM\RecordManager\DatabaseRecord;
use Modules\ORM\RecordManager\RecordManager;
use Modules\Serialisation\Serialisable;

#[BindTable(name: "books")]
class Book // extends DatabaseRecord
{
	use DatabaseRecord;

	public static function GetManager(): RecordManager {
		return RecordManager::Get(self::class);
	}


	#[
		BindField(type: "varchar"),
		Serialisable
	]
	public string $title;


	#[
		BindField(type: "text",),
		Serialisable
	]
	public string $description = '';


	#[BindField()]
	public array $author;


	#[
		BindField(type: "date"),
		Serialisable
	]
	public DateTime $publicationDate;


	#[
		BindField(type: "float"),
		Serialisable
	]
	public float $price;
}