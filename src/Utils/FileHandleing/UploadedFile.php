<?php
namespace Utils\FileHandleing;

use Interfaces\FileInterface;
use Utils\FileHandler;

//Todo: remake the old file system with this class instead
class UploadedFile implements FileInterface
{
	protected string $temp_name;
	protected string $path;
	protected string $type;
	protected int $error;

	public function __construct(string $temp_name, string $path, string $type, int $error) {
		$this->temp_name = $temp_name;
		$this->path = $path;
		$this->type = $type;
		$this->error = $error;
	}

	public function __toString(): string {
		return $this->path;
	}

	public function __destruct() {
		if (file_exists($this->temp_name)) {
			unlink($this->temp_name);
		}
		
		if (file_exists($this->path)) {
			unlink($this->path);
		}
	}

	#region getters

	public function GetPath(): string {
		return $this->path;
	}

	public function GetType(): string {
		return $this->type;
	}

	public function GetError(): string {
		return $this->error;
	}

	public function GetErrorDescription(): string {
		return FileHandler::CheckError($this->error);
	}

	public function GetTempPath(): string {
		return $this->temp_name;
	}

	public function GetSize(): int {
		return filesize($this->temp_name);
	}

	public function GetExtension(): string {
		return pathinfo($this->path, PATHINFO_EXTENSION);
	}

	public function GetName(): string {
		return pathinfo($this->path, PATHINFO_FILENAME);
	}

	#endregion getters
	
	#region checkers
	public function IsValid(): bool {
		return UPLOAD_ERR_OK === $this->error;
	}
	
	public function IsEmpty(): bool {
		return UPLOAD_ERR_NO_FILE === $this->error;
	}
	#endregion checkers

	public function Save(string $path): bool {
		return $this->Move($path);
	}

	public function Move(string $destination): bool {
		return move_uploaded_file($this->temp_name, $destination);
	}

	public function Delete(): bool {
		return unlink($this->temp_name);
	}
}