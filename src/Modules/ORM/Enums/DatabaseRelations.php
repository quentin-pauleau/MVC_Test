<?php
namespace Modules\ORM\Enums;


enum DatabaseRelations : int
{
	case NONE = 0;
	case ONE_TO_ONE = 1;
	case MANY_TO_ONE = 2;
	case ONE_TO_MANY = 3;
	case MANY_TO_MANY = 4;
}