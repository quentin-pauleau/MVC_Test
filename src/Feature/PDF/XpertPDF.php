<?php
namespace Feature\PDF;

use FPDF;

class XpertPDF extends FPDF
{
	public function __construct(
		$orientation = 'P', 
		$unit = 'mm', 
		$size = 'A4'
	) {
		parent::__construct($orientation, $unit, $size);

		$this->SetFont('Arial', 'B', 18);

		$this->AddPage();
	}

	public function Header() {

		//* logo in top left
		$this->Image(
			__DIR__.'/../../../public/Icons/logo_xpert.jpg',
			5,
			5,
			57,
			25,
		);
	}
}