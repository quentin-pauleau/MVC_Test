<?php
namespace Feature\EntityToDatabase\Interfaces;

use Utils\UUID;


interface FileLinkableInterface
{
	public function GetId(): int;
	public function GetUUID(): UUID;
}