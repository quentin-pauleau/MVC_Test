<?php
namespace Feature\EntityToDatabase\Interfaces;

use Core\UUID;


interface FileLinkableInterface
{
	public function GetId(): int;
	public function GetUUID(): UUID;
}