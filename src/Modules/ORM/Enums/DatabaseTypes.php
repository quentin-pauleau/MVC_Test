<?php
namespace Modules\ORM\Enums;


enum DatabaseTypes : string
{
	//* Basic types
	case BOOLEAN = 'tinyint(1)';
	case INT = 'int';
	case FLOAT = 'float';
	case SHORT_TEXT = 'varchar(255)';
	case TEXT = 'text';

	//* Special types
	case JSON = 'json';
	case EMAIL = 'varchar(320)';
	case SHORT_URL = 'varchar(255)';
	case URL = 'varchar(1024)';
	case LONG_URL = 'varchar(2048)';
	case PHONE_NUMBER = 'varchar(15)';
	case UUID = 'char(36)';
	case ULID = 'char(26)';

	//* Date types
	case DATETIME = 'datetime';
	case DATE = 'date';
	case TIME = 'time';
	case TIMESTAMP = 'timestamp';
}