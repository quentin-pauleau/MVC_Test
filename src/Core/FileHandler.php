<?php
namespace Core;

use Exception;

class FileHandler
{
	public static function MakeArray(string $name): array {
		$files = [];
		for ($i = 0; $i < count($_FILES[$name]["name"]); $i++) {
			$files[] = [
				"name" => basename($_FILES[$name]["name"][$i]),
				"tmp_name" => $_FILES[$name]["tmp_name"][$i],
				"error" => $_FILES[$name]["error"][$i],
				"type" => $_FILES[$name]["type"][$i],
			];
		}
		return $files;
	}

	public static function CheckError(int $errorCode): ?string {
		switch ($errorCode) {
			case \UPLOAD_ERR_OK: // 0
				return null; // returned when no error occured
			
			case \UPLOAD_ERR_INI_SIZE: // 1
				return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
			
			case \UPLOAD_ERR_FORM_SIZE: // 2
				return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
			
			case \UPLOAD_ERR_PARTIAL: // 3
				return'The uploaded file was only partially uploaded';
			
			case \UPLOAD_ERR_NO_FILE: // 4
				return 'No file was uploaded';
			
			case \UPLOAD_ERR_NO_TMP_DIR: // 6
				return 'Missing a temporary folder';
			
			case \UPLOAD_ERR_CANT_WRITE: // 7
				return 'Failed to write file to disk.';
			
			case \UPLOAD_ERR_EXTENSION: // 8
				return 'A PHP extension stopped the file upload.';
			
			default:
				return "Une erreur inconnue est survenue lors du téléchargement du fichier";
		}
	}

	public static function UploadFile(string $tempName, string $fileName, string $destinationPath): bool {
		// Vérifier si le dossier de destination existe, sinon le créer
		if (!is_dir($destinationPath))
			throw new Exception("Impossible de créer le dossier de destination");
		
		// Vérifier si le fichier est bien téléchargé
		if (!file_exists($tempName) || !is_uploaded_file($tempName))
			throw new Exception("Le fichier est invalide ou n'a pas été correctement téléchargé.");
		
		// Définir le chemin complet
		$destinationPath = rtrim($destinationPath, "\\/") . "/" . $fileName;

		
		// Déplacer le fichier temporaire vers le dossier cible
		if (!move_uploaded_file($tempName, $destinationPath)) {
			throw new Exception("Une erreur est survenue lors de l'envoi du fichier.");
		}
		
		return true;
	}

	public static function CreateAssociatedIdx(string $filePath, string $content): bool {
		$fileName = pathinfo($filePath, PATHINFO_FILENAME).".idx";
		$destinationPath = dirname($filePath);
		
		if (!is_dir($destinationPath))
			throw new Exception("L'emplacement de destionation des fichiers n'existe pas");
		
		if (file_exists("$destinationPath/$fileName"))
			throw new Exception("Un idx a déjà été associé");

		try
		{
			$file = fopen("$destinationPath/$fileName", "w");
			fwrite($file, $content);
			fclose($file);
		}
		catch (Exception $e)
		{
			throw $e;
		}

		return true;
	}

	public static function GetFileStartingWith(string $dir, string $prefix): array {
		if (!is_dir($dir)) {
			throw new Exception("Le dossier spécifié n'existe pas.");
		}
		
		$files = scandir($dir);
		$result = [];
		
		foreach ($files as $file) {
			$name = pathinfo($file, PATHINFO_FILENAME);
			if (is_file("$dir/$file") && str_starts_with2($name, $prefix)) {
				$result[] = $file;
			}
		}
		
		return $result;
	}

	public static function GetFileContainingWith(string $dir, string $str): array {
		if (!is_dir($dir)) {
			throw new Exception("Le dossier spécifié n'existe pas.");
		}
		
		$files = scandir($dir);
		$result = [];
		
		foreach ($files as $file) {
			$name = pathinfo($file, PATHINFO_FILENAME);

			if (is_file("$dir/$file") && str_contains2($name, $str))
				$result[] = $file;
		}
		
		return $result;
	}

	public static function GetFileEndingWith(string $dir, string $suffix, bool $include_path = false): array {
		if (!is_dir($dir))
			throw new Exception("Le dossier spécifié n'existe pas.");
		
		$files = scandir($dir);
		$result = [];
		
		foreach ($files as $file)
			if (is_file("$dir/$file") && str_ends_with2(pathinfo($file, PATHINFO_FILENAME), $suffix))
				$result[] = $include_path ? "$dir/$file": $file;
		
		return $result;
	}

	public static function DeleteFiles(array $files): void {
		foreach ($files as $file)
			if (file_exists($file))
				unlink($file);
	}

	public static function MoveFile(string $filePath, string $destinationPath): bool {
		// Check if the source file exists
		if (!file_exists($filePath))
			throw new Exception ("Le fichier source n'existe pas");
		
		// Check if the destination is a directory and if not use the parent directory instead
		if (!is_dir($destinationPath))
			throw new Exception ("Le dossier deestination n'existe pas");

		// Set the destination path to the destination path + the name of the file to keep the same name
		$destinationPath = rtrim($destinationPath, "\\/")."/".basename($filePath);
		
		// Attempt to move the file
		return rename($filePath, $destinationPath);
	}
}