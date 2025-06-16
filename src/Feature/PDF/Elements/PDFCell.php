<?php
namespace Feature\PDF\Elements;

require_once __DIR__.'\..\..\..\Assets\fpdf_v1_86\fpdf.php';
use FPDF;

use Feature\PDF\Color;
use Feature\PDF\Enums\PDFSides;
use Feature\PDF\Vector2;


class PDFCell extends PDFElement
{
	//* Vectors
	public ?Vector2 $Position = null;
	public ?Vector2 $Margin = null;
	public ?Vector2 $Size = null;

	//* Color
	public ?Color $FillColor = null;
	public ?Color $BorderColor = null;
	public ?Color $TextColor = null;

	//*
	public string $Text = '';
	public int $Border = PDFSides::NONE;
	public string $TextAlign = 'C';
	public int $Link;


	public function __construct(

	) {
		$this->Size = new Vector2;
		$this->Margin = new Vector2;

		$this->FillColor = null;
		$this->BorderColor = new Color;
		$this->TextColor = new Color;
	}


	public function AddToPDF(
		FPDF $PDF
	): bool {
		if ($this->Position !== null)
			$PDF->SetXY(
				$this->Position->x + $this->Margin->x, 
				$this->Position->y + $this->Margin->y
			);

		if ($this->Size !== null)
			$this->Size = Vector2::Clamp(
				$this->Size, 
				Vector2::ZERO(), 
				new Vector2($PDF->GetPageWidth(), $PDF->GetPageHeight())
			);
		
		if ($this->FillColor !== null)
			$PDF->SetFillColor($this->FillColor->r, $this->FillColor->g, $this->FillColor->b);

		if ($this->BorderColor !== null)
			$PDF->SetDrawColor($this->BorderColor->r, $this->BorderColor->g, $this->BorderColor->b);
		
		if ($this->TextColor !== null)
			$PDF->SetTextColor($this->TextColor->r, $this->TextColor->g, $this->TextColor->b);

		$PDF->Cell(
			$this->Size->x - $this->Margin->x * 2,
			$this->Size->y,

			Convert_encoding_to_iso($this->Text),
			PDFSides::GetType($this->Border),
			0,

			$this->TextAlign,

			$this->FillColor !== null,
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


	#region Colors
	public function SetFillColor(
		?Color $Color
	): self {
		$this->FillColor = $Color;
		return $this;
	}

	public function SetBorderColor(
		?Color $Color
	): self {
		$this->BorderColor = $Color;
		return $this;
	}

	public function SetTextColor(
		?Color $Color
	): self {
		$this->TextColor = $Color;
		return $this;
	}

	#endregion color

	public function SetBorder(
		int $Border
	): self {
		$this->Border = $Border;
		return $this;
	}

	public function SetText(
		string $Text
	): self {
		$this->Text = $Text;
		return $this;
	}

	public function SetTextAlign(
		string $TextAlign
	): self {
		$this->TextAlign = $TextAlign;
		return $this;
	}

	public function SetLink(
		int $Link
	): self {
		$this->Link = $Link;
		return $this;
	}
}