<?php
namespace Modules\Routing;


enum RoutingExceptionCodes: int
{
	// 1XX - General Routing Exceptions
	case GENERAL_ROUTING_EXCEPTION = 100;



	// 2XX - route definition Exceptions
	// When the routing definition is invalid, leading to the impossibility to pick a valid route

	/**
	 * The routing is invalid, the application router cannot find a valid route to use.
	 * Please check your routes definitions and try again.
	 */
	case INVALID_ROUTING_DEFINITION = 200;

	/**
	 * No controller to route to has been found.
	 */
	case NO_CONTROLLER_FOUND = 201;

	/**
	 * The controller doesnt have any route, valid or not
	 */
	case NO_ROUTES_FOUND_IN_CONTROLLER = 202;

	/**
	 * The controller is invalid, it cannot be instanciated
	 */
	case CONTROLLER_NOT_INSTANTIABLE = 203;



	// 3XX - Route Parameters Exceptions
	// When an error occured while processing the route parameters
	case INVALID_ROUTE_PARAMETERS = 300;

	/**
	 * The parameter definition is invalid, the parameter label must match its definition in the request.
	 * 
	 * The parameter label can only contain letters, numbers and underscores.
	 * And is delimited by curly braces, ex :
	 * @example 1 myurl/{name}/{1} => name and number are both valid label
	 */
	case URL_PARAMETER_INVALID_LABEL = 301;

	/**
	 * The url parameter does not match any of the defined label in the route.
	 *
	 * Solutions :
	 * - change the parameter name to match one of the defined labels => avoid this error
	 * - change the $label parmetter of the UrlParam attribute to match an existing label => 
	 * - add a new parameter binded to the parameter => avoid this error
	 */
	case URL_PARAMETER_HAS_NO_MATCHING_LABEL = 302;

	/**
	 * A required parameter is missing from the request.
	 * 
	 * Solutions : 
	 * - set the parameter as optional => avoid this error
	 * - create same route but with optional parameter => handle this specific missing parameters errors
	 * - create a 404 page => handle every missing parameters error
	 */
	case REQUIRED_PARAMETER_MISSING = 310;

	/**
	 * The parameter value in the request doesnt match the expected type.
	 * 
	 * Solutions : 
	 * - add the unexpected type as a possible type => avoid this error
	 * - add false as a possible type (false is set when the parameter is invalid) => handle the error yourself
	 */
	case PARAMETER_INVALID = 311;


	/**
	 * The get parameter type is not allowed.
	 * 
	 * The only allowed types are : 
	 * - string
	 * - int
	 * - bool (true/false) or (0/1)
	 * - float
	 * - UUID
	 * - DateTime (format YYYY-MM-DD HH:mm:ss)
	 * - DateTimeImmutable (format YYYY-MM-DD HH:mm:ss)
	 */
	case GET_PARAMETER_TYPE_NOT_ALLOWED = 330;


	/**
	 * The parameter is not bound
	 */
	case PARAMETER_IS_NOT_BOUND = 399;




	// 4XX - Response Exceptions

	/**
	 * 
	 */
	case RESPONSE_INVALID = 400;

	/**
	 * The response returned has an invalid type
	 */
	case RESPONSE_TYPE_INVALID = 401;


	/**
	 * The response content is invalid.
	 */
	case RESPONSE_CONTENT_INVALID = 402;
}