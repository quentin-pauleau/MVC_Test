<?php
namespace Feature\PDF\Elements;

require_once __DIR__.'\..\..\..\Assets\fpdf_v1_86\fpdf.php';
use FPDF;

use Feature\PDF\Vector2;

class PDFImage extends PDFElement
{
	//* Vectors
	public ?Vector2 $Position = null;
	public ?Vector2 $Margin = null;
	public ?Vector2 $Size = null;

	//*
	public string $ImagePath = '';
	public int $Link;


	public function __construct(string $ImagePath) {
		$this->ImagePath = $ImagePath;
		$this->Size = new Vector2;
		$this->Margin = new Vector2;
	}


	public function AddToPDF(
		FPDF $PDF
	): bool {
		if ($this->Size !== null)
			$this->Size = Vector2::Clamp(
				$this->Size, 
				Vector2::ZERO(), 
				new Vector2($PDF->GetPageWidth(), $PDF->GetPageHeight())
			);

		if ($this->Position !== null)
			$PDF->Image(
				$this->ImagePath,
				$this->Position->x + $this->Margin->x, 
				$this->Position->y + $this->Margin->y,

				$this->Size->x,
				$this->Size->y,

				'',
				$this->Link ?? '',
			);
		else
			$PDF->Image(
				$this->ImagePath,
				null, 
				null,

				$this->Size->x,
				$this->Size->y,

				'',
				$this->Link ?? '',
			);

		return true;
	}

	
	#region Vectors
	public function SetPosition(
		Vector2 $Position
	): self {
		$this->Position = $Position;
		return $this;
	}

	public function SetMargin(
		Vector2 $Margin
	): self {
		$this->Margin = $Margin;
		return $this;
	}

	public function SetSize(
		Vector2 $Size
	): self {
		$this->Size = $Size;
		return $this;
	}

	#endregion Vectors
	public function SetImagePath(
		string $ImagePath
	): self {
		$this->ImagePath = $ImagePath;
		return $this;
	}

	public function SetLink(
		int $Link
	): self {
		$this->Link = $Link;
		return $this;
	}
}