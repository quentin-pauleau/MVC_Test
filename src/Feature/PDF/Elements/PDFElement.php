<?php
namespace Feature\PDF\Elements;

require_once __DIR__.'\..\..\..\Assets\fpdf_v1_86\fpdf.php';
use FPDF;

abstract class PDFElement
{
	abstract public function AddToPDF(FPDF $PDF);
}