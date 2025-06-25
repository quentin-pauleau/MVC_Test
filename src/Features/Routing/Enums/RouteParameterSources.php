<?php
namespace Feature\Routing\Enums;


enum RouteParameterSources : string
{
	
	case QUERY = 'GET';
	case GET = 'GET';
	case REQUEST = 'POST';
	case POST = 'POST';

	// Others
	case SERVER = 'SERVER';
	case FILE = 'FILES';
	case COOKIE = 'COOKIES';
}