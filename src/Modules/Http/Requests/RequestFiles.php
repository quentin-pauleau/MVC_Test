<?php
namespace Core\Requests;

use Core\FileHandleing\ListUploadedFile;
use Core\FileHandleing\UploadedFile;
use Core\FileHandler;

/**
 * 
 * @template-contravariant string
 * @template-covariant ListUploadedFile
 * @template-extends RequestDataAbstract<string, mixed|iterable>
 */
class RequestFiles extends RequestDataAbstract
{
	// FILE_KEYS = ['error', 'full_path', 'name', 'size', 'tmp_name', 'type'];

	/**
	 * @var array<string, ListUploadedFile>
	 */
	protected array $values = [];

	
	public function __construct() {

		// $_FILES support file with one depth, like : 
		// $_FILES[ {input name} ] = [
		// 	'error' => 0,
		// 	'full_path' => 'path/to/file',
		// 	'name' => 'file.txt',
		// 	'size' => 1234,
		// 	'tmp_name' => 'tmp/path/to/file',
		// 	'type' => 'text/plain'
		// ];
		
		foreach ($_FILES as $name => $t) {
			$files = FileHandler::MakeArray($name);

			foreach($files as $file) {
				$FileList = new ListUploadedFile();
				if (\UPLOAD_ERR_NO_FILE === $file['error']) {
					$file = null;
					continue;
				}

				$FileList->Add(
					new UploadedFile(
						$file['tmp_name'], 
						$file['full_path'] ?? $file['name'], 
						$file['type'], 
						$file['error']
					)
				);
			}
			
			$this->Add($name, $FileList);
		}
	}


	public function Get(string $name): ?ListUploadedFile {
		return $this->values[$name] ?? null;
	}

	
	public function current(): ?ListUploadedFile {
		return $this->values[$this->current_name];
	}
}