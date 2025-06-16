<?php
namespace Services\ServicesDemandeConges;

use Feature\PDF\Color;
use Feature\PDF\Elements\PDFImage;
use Feature\PDF\Elements\PDFText;
use Feature\PDF\Enums\PDFFontStyle;
use Feature\PDF\PDF;
use Feature\PDF\Vector2;
use Feature\PDF\XpertPDF;
use Interfaces\ServiceExportInterface;
use Models\Entities\DemandeConges;
use Services\ServicePDF;

class ServiceDemandeCongesPDF implements ServiceExportInterface
{
	private const DESTINATION_FOLDER = __DIR__.'\..\..\..\temp\demande_conges'; 
	private const CHECKBOX_CHECKED_IMG_PATH = __DIR__.'\..\..\..\public\Icons\check_box_checked.png';
	private const CHECKBOX_UNCHECKED_IMG_PATH = __DIR__.'\..\..\..\public\Icons\check_box_unchecked.png';
	private const PDF_CLASS = XpertPDF::class;

	private ServicePDF $ServicePDF;
	private PDF $PDF;

	private bool $IsLazyDataLoaded = false;

	private int $CurrentX;
	private int $CurrentY;

	private Vector2 $Margin;

	private string $family;

	public function __construct() {	
		$this->ServicePDF = new ServicePDF;
		
		$this->Margin = new Vector2(30, 5);
		$this->family = 'Arial';
	}

	public function GetTextTemplate(): PDFText {
		return (new PDFText)
			->SetHeight(20)
			->SetMargin($this->Margin)
			->SetFont(
				$this->family,
				PDFFontStyle::DEFAULT,
				14
			)
		;
	}

	public function GetLabelTextTemplate(): PDFText {
		return $this->GetTextTemplate()		
			->SetFont(
				$this->family,
				PDFFontStyle::BOLD,
			)
		;
	}

	public function GetCheckBoxTextTemplate(): PDFText {
		return $this->GetLabelTextTemplate()
			->SetHeight(15)
		;
	}

	public function GetCheckBoxImageTemplate(): PDFImage {
		return (new PDFImage(''))
			->SetSize(new Vector2(6, 6))
			->SetMargin($this->Margin)
		;
	}

	/**
	 * Create the PDF related to the 'demande conges'
	 * @param \Models\Entities\DemandeConges $DemandeConges demande the PDF is based on
	 * @param string $path
	 * @return bool
	 */
	public function CreatePDF(DemandeConges $DemandeConges): bool {
		$this->PDF = $this->ServicePDF->NewPDF(XpertPDF::class);

		$this->CurrentY = 0;
		$TextGapY = 20;
		$SecrtionGapY = 10;

		$this->PDF->SetMargin($this->Margin);

		//* Document Details
		$this->CurrentY = 30;
		
		$this->AddHeader();

		$this->CurrentY += 20;

		$this->AddDetails($DemandeConges);
		
		$this->CurrentY += $TextGapY;
		
		//* Dates
		if ($DemandeConges->StartDate == $DemandeConges->EndDate)
			$this->AddOneDate($DemandeConges);
		else
			$this->AddStartAndEndDate($DemandeConges);

		
		//* State
		$this->CurrentY += $SecrtionGapY;

		$this->PDF
			->AddElement($this->GetTextTemplate()
				->SetPosition(new Vector2(0, $this->CurrentY))
				->SetFont('', PDFFontStyle::BOLD, 14)
				->SetText(
					"Statut : "
				)
			)
			->AddElement($this->GetTextTemplate()
				->SetFont($this->family, PDFFontStyle::DEFAULT, 14)
				->SetText(
					$DemandeConges->state
				)
			)
		;

		return true;
	}

	public function Export(string $path = self::DESTINATION_FOLDER): bool {
		return $this->ServicePDF->Export($path);
	}

	private function AddHeader() {
		//* Document Title
		$this->PDF->MakeCell()
			->SetPosition(new Vector2(0, $this->CurrentY))
			->SetSize(new Vector2($this->PDF->GetPageWidth(), 15))
			->SetMargin($this->Margin)

			->SetFillColor(new Color(210, 210, 240))
			->SetText('Demande de conges')
			->SetTextAlign('C')
		;
	}


	private function AddDetails(DemandeConges $DemandeConges) {
		$this->PDF
			//* Demandeur
			->AddElement($this->GetLabelTextTemplate()
				->SetPosition(new Vector2(0, $this->CurrentY))
				->SetFont(
					$this->family,
					PDFFontStyle::BOLD,
					14
				)
				->SetText(
					"NOM Prénom : "
				)
			)
			->AddElement($this->GetTextTemplate()
				->SetText(
					"$DemandeConges->Demandeur\n"
				)
			)

			//* Type
			->AddElement($this->GetLabelTextTemplate()
				->SetText(
					"Type de congès : "
				)
			)
			->AddElement($this->GetTextTemplate()
				->SetText(
					"$DemandeConges->TypeConges\n"
				)
			)
		;
	}


	private function AddOneDate(DemandeConges $DemandeConges) {$this->CurrentX = 0;
		$GapY = 8; // space between the date and the checkbox
		$GapX = 30; // space between the checkboxs (text inlcuded)
		$CheckboxGapX = 5; // space between the checkbox and its text

		//* Start Date
		$this->PDF
			->AddElement($this->GetLabelTextTemplate()
				->SetPosition(new Vector2(0, $this->CurrentY))
				->SetText(
					"Le : "
				)
			)
			->AddElement($this->GetTextTemplate()
				->SetText(
					$DemandeConges->StartDate->format('d/m/Y')."\n"
				)
			)
		;
		
		$this->CurrentY += $GapY;

		$this->PDF->AddElement($this->GetCheckBoxImageTemplate()
			->SetImagePath(
				$DemandeConges->StartMorning && !$DemandeConges->EndAfternoon
				? self::CHECKBOX_CHECKED_IMG_PATH
				: self::CHECKBOX_UNCHECKED_IMG_PATH
			)
			->SetPosition(new Vector2($this->CurrentX, $this->CurrentY))
		);

		$this->CurrentX += $CheckboxGapX;

		$this->PDF->AddElement($this->GetCheckBoxTextTemplate()
			->SetPosition(new Vector2($this->CurrentX, $this->CurrentY))
			->SetText('Matin')
		);

		$this->CurrentX += $GapX;
		
		$this->PDF->AddElement($this->GetCheckBoxImageTemplate()
			->SetImagePath(!$DemandeConges->StartMorning && $DemandeConges->EndAfternoon
				? self::CHECKBOX_CHECKED_IMG_PATH
				: self::CHECKBOX_UNCHECKED_IMG_PATH
			)
			->SetPosition(new Vector2($this->CurrentX, $this->CurrentY))
		);
		
		$this->CurrentX += $CheckboxGapX;

		$this->PDF->AddElement($this->GetCheckBoxTextTemplate()
			->SetPosition(new Vector2($this->CurrentX, $this->CurrentY))
			->SetText('Après-Midi')
		);
		
		$this->CurrentX += $GapX;

		$this->PDF->AddElement($this->GetCheckBoxImageTemplate()
			->SetImagePath($DemandeConges->StartMorning && $DemandeConges->EndAfternoon
				? self::CHECKBOX_CHECKED_IMG_PATH
				: self::CHECKBOX_UNCHECKED_IMG_PATH
			)
			->SetPosition(new Vector2($this->CurrentX, $this->CurrentY))
		);
		
		$this->CurrentX += $CheckboxGapX;

		$this->PDF->AddElement($this->GetCheckBoxTextTemplate()
			->SetPosition(new Vector2($this->CurrentX, $this->CurrentY))
			->SetText('Journée')
		);
		
	}


	public function AddStartAndEndDate(DemandeConges $DemandeConges) {
		$this->CurrentX = 0;
		$GapY = 8; // space between the date and the checkbox
		$GapX = 20; // space between the checkboxs (text inlcuded)
		$CheckboxGapX = 5; // space between the checkbox and its text

		$DatePosY = $this->CurrentY;

		$LeftPositionX = $this->PDF->GetPageWidth() / 2 - $this->Margin->x;

		//* Start Date
		// date
		$this->PDF
			->AddElement($this->GetLabelTextTemplate()
				->SetPosition(new Vector2($this->CurrentX, $this->CurrentY))
				->SetText(
					"Du : "
				)
			)
			->AddElement($this->GetTextTemplate()
				->SetText(
					$DemandeConges->StartDate->format('d/m/Y')."\n"
				)
			)
		;

		$this->CurrentY += $GapY;
		
		// 1st checkbox
		$this->PDF->AddElement($this->GetCheckBoxImageTemplate()
			->SetImagePath(
				$DemandeConges->StartMorning 
				? self::CHECKBOX_CHECKED_IMG_PATH
				: self::CHECKBOX_UNCHECKED_IMG_PATH
			)
			->SetPosition(new Vector2($this->CurrentX, $this->CurrentY))
		);

		$this->CurrentX += $CheckboxGapX;

		$this->PDF->AddElement($this->GetCheckBoxTextTemplate()
			->SetPosition(new Vector2($this->CurrentX, $this->CurrentY))
			->SetText('Matin')
		);

		$this->CurrentX += $GapX;

		// 2nd checkbox
		$this->PDF->AddElement($this->GetCheckBoxImageTemplate()
			->SetImagePath(!$DemandeConges->StartMorning 
				? self::CHECKBOX_CHECKED_IMG_PATH
				: self::CHECKBOX_UNCHECKED_IMG_PATH
			)
			->SetPosition(new Vector2($this->CurrentX, $this->CurrentY))
		);

		$this->CurrentX += $CheckboxGapX;

		$this->PDF->AddElement($this->GetCheckBoxTextTemplate()
			->SetPosition(new Vector2($this->CurrentX, $this->CurrentY))
			->SetText('Après-Midi')
		);

		//* End Date
		$this->CurrentX = $LeftPositionX;
		$this->CurrentY = $DatePosY;
		
		$this->PDF
			->AddElement($this->GetLabelTextTemplate()
				->SetPosition(new Vector2($this->CurrentX, $this->CurrentY))
				->SetText(
					"Au : "
				)
			)
			->AddElement($this->GetTextTemplate()
				->SetText(
					$DemandeConges->EndDate->format('d/m/Y')."\n"
				)
			)
		;
		
		$this->CurrentY += $GapY;

		$this->PDF->AddElement($this->GetCheckBoxImageTemplate()
			->SetImagePath(!$DemandeConges->EndAfternoon 
				? self::CHECKBOX_CHECKED_IMG_PATH
				: self::CHECKBOX_UNCHECKED_IMG_PATH
			)
			->SetPosition(new Vector2($this->CurrentX, $this->CurrentY))
		);

		$this->CurrentX += $CheckboxGapX;

		$this->PDF->AddElement($this->GetCheckBoxTextTemplate()
			->SetPosition(new Vector2($this->CurrentX, $this->CurrentY))
			->SetText('Matin')
		);

		$this->CurrentX += $GapX;
		
		$this->PDF->AddElement($this->GetCheckBoxImageTemplate()
			->SetImagePath($DemandeConges->EndAfternoon 
				? self::CHECKBOX_CHECKED_IMG_PATH
				: self::CHECKBOX_UNCHECKED_IMG_PATH
			)
			->SetPosition(new Vector2($this->CurrentX, $this->CurrentY))
		);

		$this->CurrentX += $CheckboxGapX;

		$this->PDF->AddElement( $this->GetCheckBoxTextTemplate()
			->SetPosition(new Vector2($this->CurrentX, $this->CurrentY))
			->SetText('Après-Midi')
		);
	}
}