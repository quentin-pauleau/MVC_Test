<?php
namespace Services;

use Interfaces\ServiceInterface;
use Core\FileHandleing\ListUploadedFile;
use Core\FileHandler;
use Core\Session\ErrorHelper;
use Core\UUID;


/**
 * Service export GED export file from the app to the GED
 * 
 * Files are first imported in a temporary directory (by default : {@see self::TEMP_DIR_DEFAULT}),
 * then moved to a directory in the GED
 */
class ServiceExportUploadedFiles implements ServiceInterface
{
	private const TEMP_DIR_DEFAULT = 'temp/default/';
	private const MAX_SIZE = 10 * 1024 * 1024; // Max file size (10Mo)

	/**
	 * Path of the temporary directory
	 * @var string
	 */
	public string $tempDir = self::TEMP_DIR_DEFAULT;

	/**
	 * UUID to associated to the images
	 * @var UUID
	 */
	public ?UUID $UUID = null;

	/**
	 * files stored in the temporary folder by the service
	 * @var string[]
	 */
	public array $files = [];

	/**
	 * idx files liked to the temporary files
	 * @var string[]
	 */
	public array $idxFiles = [];


	public function __construct(
		string $tempDir = self::TEMP_DIR_DEFAULT,
		UUID $UUID = null
	) {
		$this->tempDir = $tempDir;
		$this->UUID = $UUID ?? new UUID;
	}


	/**
	 * Save files in the temp directory
	 * @param string $source Name in the $_FILES global variable
	 * @return bool
	 */
	public function SaveTemporary(string $source, ?array $allowedtypes = null): bool {
		$files = FileHandler::MakeArray("join_images");

		$fileCount = count(FileHandler::GetFileEndingWith($this->tempDir, $this->UUID));

		foreach ($files as $key => $file) {
			$error = FileHandler::CheckError($file["error"]);
			if ($error != null) {
				ErrorHelper::Set(
					"file_".$file["name"], 
					"Une erreur est survenue lors de l'envoi de l'image ".$file["name"]
						." :<br> $error"
				);
				continue;
			}

			try
			{
				$fileExtension = pathinfo($file["name"], PATHINFO_EXTENSION);
				
				$n = $key + $fileCount + 1;
				FileHandler::UploadFile(
					$file["tmp_name"], 
					$n."_$this->UUID.$fileExtension", 
					$this->tempDir,
				);
			}
			catch(\Exception $e)
			{
				ErrorHelper::Set(
					"file_".$file["name"], 
					$e->getMessage()
				);
				continue;
			}
		}

		return true;
	}


	/**
	 * Save files in the temp directory
	 * @param string $source Name in the $_FILES global variable
	 * @return bool
	 */
	public function SaveTemporaryNew(ListUploadedFile $UploadedFiles, ?array $allowedTypes = null): bool {
		$fileCount = count(FileHandler::GetFileEndingWith($this->tempDir, $this->UUID));

		foreach ($UploadedFiles as $key => $UploadedFile) {
			// verify if the file is uploaded without error
			$error = FileHandler::CheckError($UploadedFile->GetError());
			if ($error != null) {
				ErrorHelper::Set(
					"file_".$UploadedFile->GetName(), 
					"Une erreur est survenue lors de l'envoi de l'image ".$UploadedFile->GetName()
						." :<br> $error"
				);
				continue;
			}

			// verify if the file size is allowed
			if ($UploadedFile->GetSize() > self::MAX_SIZE) {
				ErrorHelper::Set(
					"file_".$UploadedFile->GetName(), 
					"Le fichier ".$UploadedFile->GetName()." est trop lourd (max 10Mo)"
				);
				continue;
			}

			// verify if the mime is allowed
			if ($allowedTypes != null && !array_search($UploadedFile->GetExtension(), $allowedTypes)) {
				ErrorHelper::Set(
					"file_".$UploadedFile->GetName(), 
					"Le fichier ".$UploadedFile->GetName()."est d'une extension de fichier non autorisée.<br>"
						."Extensions autorisées : "
						.htmlspecialchars(implode(", ", $allowedTypes))
				);
				continue;
			}

			try
			{
				$fileExtension = $UploadedFile->GetExtension();
				
				$n = $key + $fileCount + 1;

				// Upload the file
				FileHandler::UploadFile(
					$UploadedFile->GetTempPath(), 
					$n."_$this->UUID.$fileExtension", 
					$this->tempDir,
				);
			}
			catch(\Exception $e)
			{
				ErrorHelper::Set(
					"file_".$UploadedFile->GetName(), 
					$e
				);
				continue;
			}
		}

		return true;
	}

	/**
	 * Move all files stored in the temp directory to the ged directory
	 * @param string $gedDirectory path to the ged directory
	 * @return bool
	 */
	public function Commit(string $gedDirectory, string $UUIDFieldName): bool {
		try
		{		
			$this->files = FileHandler::GetFileEndingWith($this->tempDir, $this->UUID);
			$this->idxFiles = [];

			foreach ($this->files as $key => $file) {
				$sourceFilePath = $this->tempDir . $file;
				
				// Move the file to the destination directory (keeps the same filename)
				if (!FileHandler::MoveFile($sourceFilePath, $gedDirectory))
					throw new \Exception("Une erreur est survenue lors de la liaison des fichiers joints");
				
				// Remember that this file was moved (store just the filename)
				$this->files[$key] = $file;
				
				// Create an associated idx file in the destination directory
				FileHandler::CreateAssociatedIdx(
					"$gedDirectory/$file", 
					"$UUIDFieldName = $this->UUID"
				);
				
				// Compute the idx filename: replace the extension with ".idx"
				$idxFilename = pathinfo($file, PATHINFO_FILENAME).'.idx';
				$this->idxFiles[] = "$gedDirectory/$idxFilename";
			}
		}
		catch (\Exception $e)
		{
			ErrorHelper::Set("files", $e->getMessage());
			return true;
		}

		return true;
	}

	/**
	 * Try to rollback the commited files and retrieve them to the temporary folder
	 * @param string $gedDirectory
	 * @return bool
	 */
	public function Rollback(string $gedDirectory): bool {
		try 
		{
			// For each moved file, move it back to the source directory
			foreach ($this->files as $file) {
				$destinationFilePath = rtrim($gedDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file;
				FileHandler::MoveFile($destinationFilePath, $this->tempDir);
			}
		}
		catch (\Exception $e)
		{
			ErrorHelper::Set("files", $e->getMessage(), ErrorHelper::TYPE_DEBUG);
		}
		
		// Delete the associated idx files
		FileHandler::DeleteFiles($this->idxFiles);
		return true;
	}
}