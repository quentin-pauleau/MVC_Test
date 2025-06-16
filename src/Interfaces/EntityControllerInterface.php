<?php
namespace Interfaces;

use Utils\Responses\Response;

/**
 * Interface for controller managing entities
 */
interface EntityControllerInterface
{
	/**
	 * Default action of the controller
	 * @return void
	 */
	public function Index(): Response;

	/**
	 * List of entities
	 * @return void
	 */
	public function List(): Response;

	/**
	 * Details of an entity
	 * @return void
	 */
	public function Details(): Response;

	/**
	 * Create a new entity
	 * @return void
	 */
	public function New(): Response;
}