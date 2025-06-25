<?php
namespace Feature\PDF;

require_once __DIR__.'\..\..\Assets\fpdf_v1_86\fpdf.php';
use FPDF;

use Exception;
use Feature\PDF\Elements\PDFCell;
use Feature\PDF\Elements\PDFElement;
use Feature\PDF\Elements\PDFImage;
use Feature\PDF\Elements\PDFText;
use Feature\PDF\Vector2;


use Interfaces\FileInterface;

use Feature\PDF\Enums\PDFOrientations as Orientations;
use Feature\PDF\Enums\PDFUnits as Units;
use Feature\PDF\Enums\PDFSizes as Sizes;

use Core\UUID;


class PDF implements FileInterface
{
	protected FPDF $PDF;

	/**
	 * @var PDFElement[]
	 */
	public array $Elements = [];

	protected string $default_orientation;
	protected string $default_unit;
	protected string $default_size;

	public function __construct(
		int $Orientation = Orientations::PORTRAIT,
		int $Unit = Units::MILLIMETERS,
		int $Size = Sizes::A4,
		string $PDFClass = FPDF::class
	) {
		$this->default_orientation = Orientations::GetType($Orientation);
		$this->default_unit = Units::GetType($Unit);
		$this->default_size = Sizes::GetType($Size);

		$this->PDF = new $PDFClass(
			$this->default_orientation,
			$this->default_unit,
			$this->default_size,
		);
	}


	#region Getters
	public function GetPosition(): Vector2 {
		return new Vector2(
			$this->PDF->GetX(), 
			$this->PDF->GetY()
		);
	}


	public function GetPageSize(): Vector2 {
		return new Vector2(
			$this->PDF->GetPageWidth(),
			$this->PDF->GetPageHeight()
		);
	}


	public function GetPageWidth(): float {
		return $this->PDF->GetPageWidth();
	}


	public function GetPageHeight(): float {
		return $this->PDF->GetPageHeight();
	}


	#endregion Getters


	#region Setters
	public function SetPosition(
		Vector2 $position
	): self {
		$position = Vector2::Clamp(
			$position,
			Vector2::ZERO(),
			$this->GetPageSize(),
		);

		$this->PDF->SetXY(
			$position->x,
			$position->y,
		);
		return $this;
	}

	public function SetMargin(
		Vector2 $Margins
	): self {

		$this->PDF->SetMargins(
			$Margins->x,
			$Margins->y,
			$Margins->x,
		);

		return $this;
	}

	
	#endregion Setters


	#region Move
	public function Move(
		Vector2 $amount
	): Vector2 {
		$this->SetPosition(
			Vector2::Add($this->GetPosition(), $amount)
		);
		return $this->GetPosition();
	}


	public function MoveToStart(int $y): Vector2 {
		$this->SetPosition(
			new Vector2(0, $this->PDF->GetY() + $y)
		);
		return $this->GetPosition();
	}

	
	#endregion Move


	public function Save(string $path, ?string $name = null): bool {
		$name ??= new UUID;

		$this->BuildElements();
		
		$this->PDF->Output(
			'F',
			$path.DIRECTORY_SEPARATOR.$name.'.pdf',
		);
		
		return true;
	}


	public function Show(): bool {
		$this->BuildElements();
		$this->PDF->Output('I');
		return true;
	}

	public function Add(PDFElement ...$Element): bool {
		return $this->AddArray($Element);
	}


	/**
	 * @param PDFElement[] $Element
	 * @throws \Exception
	 * @return bool
	 */
	public function AddArray(array $Element): bool {
		foreach ($this->Elements as $Element)
			if (!$Element->AddToPDF($this->PDF))
				throw new Exception('Impossible to build the PDF');
		
		return true;
	}


	protected function BuildElements (): bool {
		return $this->AddArray($this->Elements);
	}


	public function AddPage(
		?int $Orientation = null,
		?int $Size = null
	): self {
		$this->PDF->AddPage(
			$Orientation !== null 
			? Orientations::GetType($Orientation) 
			: $this->default_orientation,

			$Size !== null 
			? Sizes::GetType($Size) 
			: $this->default_size,
		);
		return $this;
	}


	#region Text 
	public function AddText(string $text, float $h = 5): self {
		$this->PDF->Write($h, $text);

		return $this;
	}

	#endregion Text

	public function AddElement(PDFElement $Element) : self {
		$this->Elements[] = $Element;
		return $this;
	}
	
	public function MakeCell(): PDFCell {
		return $this->Elements[] = (new PDFCell())
			->SetPosition($this->GetPosition());
	}

	public function MakeText(): PDFText {
		return $this->Elements[] = (new PDFText())
			->SetPosition($this->GetPosition());
	}

	public function MakeImage(string $ImagePath): PDFImage {
		return $this->Elements[] = (new PDFImage($ImagePath))
			->SetPosition($this->GetPosition());
	}
}