<?php
namespace Feature\Services\Interfaces;


/**
 * An interface for factory services
 */
interface FactoryServiceInterface extends ServiceInterface
{

	/**
	 * Create a new instance of the factory's product
	 * @return void
	 */
	public function Create(): object;
}