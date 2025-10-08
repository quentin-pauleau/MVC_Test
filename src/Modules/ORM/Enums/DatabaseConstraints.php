<?php
namespace Modules\ORM\Enums;

enum DatabaseConstraints : string
{
	case PRIMARY_KEY = 'PRIMARY KEY';
	case FOREIGN_KEY = 'FOREIGN KEY';
	case INDEX = 'INDEX';
	case UNIQUE = 'UNIQUE';
}