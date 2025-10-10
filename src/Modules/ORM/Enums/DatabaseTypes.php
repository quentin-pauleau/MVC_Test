<?php
namespace Modules\ORM\Enums;


enum DatabaseTypes : string
{
	//* Basic types
	/**
	 * For Booleans
	 * Default for boolean values.
	 */
	case BOOL = 'BOOLEAN';

	/**
	 * For Integers
	 * 
	 * Default for integer values.
	 */
	case INT = 'int';

	/**
	 * For Floats
	 * 
	 * Default for float values.
	 */
	case FLOAT = 'float';

	/**
	 * For Doubles
	 * 
	 */
	case REAL = 'real';

	//* Text types
	/**
	 * For Short texts (up to 255 characters).
	 * 
	 * Default type for string
	 */
	case SHORT_TEXT = 'varchar(255)';

	/**
	 * For texts up to 65,535 characters.
	 */
	case TEXT = 'text';

	//* Binary types
	/**
	 * For UUID, which are 16 bytes in length.
	 * Default type for UUIDs
	 * 
	 * It is recommended to use the offcial UUID type if your database as one
	 * 
	 * {@see self::UUID_TEXT} for text representation of UUIDs.
	 */
	case UUID_BINARY = 'binary(16)';
	
	/**
	 * For ULID, which are 10 bytes in length.
	 * Default type for ULID
	 * 
	 * It is recommended to use the offcial ULID type if your database as one
	 * 
	 * {@see self::ULID_TEXT} for text representation of ULID.
	 */
	case ULID_BINARY = 'binary(10)';


	//* Date types
	/**
	 * For Date and time
	 * 
	 * Default type for DateTime objects.
	 */
	case DATETIME = 'datetime';

	/**
	 * For Date only
	 */
	case DATE = 'date';

	/**
	 * For Time only
	 */
	case TIME = 'time';

	/**
	 * For Date interval
	 */
	case INTERVAL = 'interval';


	

	//* Special types

	/**
	 * For json
	 */
	case JSON = 'json';

	/**
	 * For email addresses, which are limited to 320 characters by RFC 5321.
	 */
	case EMAIL = 'varchar(320)';

	/**
	 * For urls up to 255 characters long.
	 */
	case SHORT_URL = self::SHORT_TEXT;

	/**
	 * For urls up to 1024 characters long.
	 */
	case URL = 'varchar(1024)';

	/**
	 * For URLs up to 2048 characters long.
	 */
	case LONG_URL = 'varchar(2048)';
	case PHONE_NUMBER = 'varchar(15)';

	/**
	 * For UUID as a string
	 * It is recommended to use the official UUID type if your database as one
	 *
	 * {@see self::UUID_BINARY} for binary representation of UUID.
	 */
	case UUID_TEXT = 'char(36)';

	/**
	 * For ULID as a string.
	 * It is recommended to use the official ULID if your database as one
	 * 
	 * {@see self::ULID_BINARY} for binary representation of ULID.
	 */
	case ULID_TEXT = 'char(26)';

}