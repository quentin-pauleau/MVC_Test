<?php
namespace Feature\EntityToDatabase\Enums;


enum DatabaseTypes : string
{
	//* Basic types
	case BOOL = 'boolean';
	case INT = 'int';
	case FLOAT = 'float';
	case STRING = 'string';


	//* Date types
	case DATETIME = 'datetime';
	case DATE = 'date';
	case TIME = 'time';
	// case TIMESTAMP = 'timestamp';
}