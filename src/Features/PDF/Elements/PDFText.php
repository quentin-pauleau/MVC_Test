<?php
namespace Feature\PDF\Elements;

require_once __DIR__.'\..\..\..\Assets\fpdf_v1_86\fpdf.php';
use FPDF;

use Feature\PDF\Color;
use Feature\PDF\Enums\PDFFontStyle;
use Feature\PDF\Enums\PDFSides;
use Feature\PDF\Vector2;


class PDFText extends PDFElement
{
	//* Vectors
	public ?Vector2 $Position = null;
	public ?Vector2 $Margin = null;
	public ?float $Height = 0;

	//* Text
	public ?Color $TextColor = null;
	public ?string $FontFamilly = null;
	public int $FontSize;
	public int $FontStyle;

	//*
	public string $Text = '';
	public int $Border = PDFSides::NONE;
	public string $TextAlign = 'C';
	public int $Link;


	public function __construct() {
		$this->Margin = new Vector2;

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

		if ($this->Height !== null)
			$this->Height = clampf($this->Height, 0, $PDF->GetPageHeight());
		
		if ($this->TextColor !== null)
			$PDF->SetTextColor($this->TextColor->r, $this->TextColor->g, $this->TextColor->b);

		if ($this->FontFamilly !== null)
			$PDF->SetFont(
				$this->FontFamilly, 
				PDFFontStyle::GetType($this->FontStyle ?? PDFFontStyle::DEFAULT), 
				$this->FontSize ?? 0
			);
		elseif($this->FontSize !== null)
			$PDF->SetFontSize($this->FontSize);

		$PDF->Write(
			$this->Height - $this->Margin->y * 2,
			Convert_encoding_to_iso($this->Text),
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

	public function SetHeight(
		float $Height
	): self {
		$this->Height = $Height;
		return $this;
	}
	#endregion Vectors


	#region Colors

	public function SetTextColor(
		?Color $Color
	): self {
		$this->TextColor = $Color;
		return $this;
	}

	#endregion color

	public function SetFont(
		string $family,
		int $style,
		int $size = 0
	): self {
		$this->FontFamilly = $family;
		$this->FontStyle = $style;
		$this->FontSize = $size;
		return $this;
	}

	public function SetFontSize(
		int $size
	): self {
		$this->FontSize = $size;
		return $this;
	}

	public function SetText(
		string $Text
	): self {
		$this->Text = $Text;
		return $this;
	}

	public function SetLink(
		int $Link
	): self {
		$this->Link = $Link;
		return $this;
	}
}