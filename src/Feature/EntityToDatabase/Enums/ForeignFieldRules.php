<?php
namespace Feature\EntityToDatabase\Enums;


enum ForeignFieldRules : int
{
	/**
	 * Changes made to the foreign field will not affect this field
	 */
	case NONE = 0;

	/**
	 * Changes made to the foreign field will set this field to null
	 */
	case SET_NULL = 1;

	/**
	 * Changes made to the foreign field will be replicated on this field
	 */
	case CASCADE = 2;

	/**
	 * Changes made to the foreign field will be cancelled and an exception will be thrown
	 */
	case RESTRICT = 3;

	/**
	 * Changes made to the foreign field will set this field to the default value
	 */
	case SET_DEFAULT = 4;

	/**
	 * Changes made to the foreign field will set this field to a defined value
	 */
	case SET_TO_VALUE = 5;
}