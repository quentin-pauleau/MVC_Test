<?php
namespace Utils\Requests;

use Traits\StaticClass;


/**
 * Enumeration of HTTP methods
 * (enum)
 */
class RequestMethods
{
	use StaticClass;
	public const METHOD_HEAD = 'HEAD';

	/**
	 * Requests a representation of the specified resource
	 * @var string
	 */
	public const GET = 'GET';

	/**
	 * Request sending data to the server
	 * 
	 * or
	 * 
	 * Request a insertion of the specified resource
	 * @var string
	 */
	public const POST = 'POST';

	/**
	 * Data modification of the specified resource
	 * @var string
	 */
	public const PUT = 'PUT';

	/**
	 * Request a partial modification of the specified resource
	 * @var string
	 */
	public const PATCH = 'PATCH';

	/**
	 * Requests a deletion of the specified resource
	 * @var string
	 */
	public const DELETE = 'DELETE';

	public const PURGE = 'PURGE';
	public const OPTIONS = 'OPTIONS';
	public const TRACE = 'TRACE';
	public const CONNECT = 'CONNECT';
}