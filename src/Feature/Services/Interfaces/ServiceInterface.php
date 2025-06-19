<?php
namespace Feature\Services\Interfaces;

/**
 * Services handle repetitive functionnality
 */
interface ServiceInterface
{
	/**
	 * Create a new instance of the service, no parameters required
	 */
	public function __construct();
}