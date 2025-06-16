<?php
namespace Interfaces;

interface ServiceExportInterface extends ServiceInterface
{
	/**
	 * Export a file in the GED
	 * @param string $path the path to the folder where the file will be exported
	 * @return bool true if the file was exported successfully, false otherwise
	 */
	public function Export(string $path): bool;
}