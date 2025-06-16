<?php
namespace Services;

use Interfaces\ServiceExportInterface;
use Interfaces\ServiceInterface;

use Feature\PDF\Enums\PDFOrientations as Orientations;
use Feature\PDF\Enums\PDFUnits as Units;
use Feature\PDF\Enums\PDFSizes as Sizes;

use Feature\PDF\PDF;

class ServicePDF implements ServiceInterface, ServiceExportInterface
{
	/**
	 * @var array<int, PDF> $PDFs
	 */
	protected array $PDFs = [];

	public function __construct() {}


	/**
	 * Add a new PDF to the service
	 * @param mixed $PDFClass
	 * @param int $Orientation 
	 * 
	 * @param int $Unit
	 * @param int $Size
	 * @return PDF the PDF Builder of the new PDF
	 */
	public function NewPDF(
		?string $PDFClass = null,
		int $Orientation = Orientations::PORTRAIT,
		int $Unit = Units::MILLIMETERS,
		int $Size = Sizes::A4
	): PDF {
		$PDF = ($PDFClass != null) 
			? new PDF(
				$Orientation,
				$Unit,
				$Size,
				$PDFClass
			)
			: $PDF = new PDF(
				$Orientation,
				$Unit,
				$Size,
			);
		
		$this->PDFs[] = $PDF;

		return $PDF;
	}

	public function GetPDF(int $num): PDF {
		return $this->PDFs[$num];
	}


	public function Export(string $path): bool {
		foreach ($this->PDFs as $PDF)
			if (!$PDF->Save($path))
				return false;
		
		return true;
	}
}