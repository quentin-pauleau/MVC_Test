<?php
namespace Interfaces;

/**
 * Interface for file handling
 */
interface FileInterface
{
	/**
	 * Save the file in a given folder
	 * @param string $path the path to the folder where the file will be saved
	 * @return bool true if the file was saved successfully, false otherwise
	 */
	public function Save(string $path): bool;
}