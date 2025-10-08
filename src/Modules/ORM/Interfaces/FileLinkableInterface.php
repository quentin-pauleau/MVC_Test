<?php
namespace Modules\ORM\Interfaces;

use Core\UUID;


interface FileLinkableInterface
{
	public function GetId(): int;
	public function GetUUID(): UUID;
}