<?php
namespace Services\ServicesDemandeConges;

use DateTime;
use Interfaces\FormServiceInterface;
use Models\Entities\DemandeConges;
use Core\Requests\Request;
use Core\Session\ErrorHelper;

class FormServiceDemandeConges implements FormServiceInterface
{
	public function __construct() { }

	public function Handle(Request $Request, object $DemandeConges): bool {
		$result = true;

		if ($DemandeConges instanceof DemandeConges === false)
			throw new \Exception('DemandeConges must be an instance of DemandeConges');

		switch ($id_type_conges = $Request->Data->FilterInt("type_conges")) {
			case null:
				ErrorHelper::Set("type_conges", "Le type de conges est obligatoire");
				$result = false;
				break;

			case false:
				ErrorHelper::Set("type_conges", "Le type de conges est invalide");
				$result = false;
				break;

			default:
				$DemandeConges->id_type_conges = $id_type_conges;
				break;
		}


		switch ($StartDate = $Request->Data->FilterString("date_debut")) {
			case null:
				ErrorHelper::Set("date_debut", "La date de début est obligatoire");
				$result = false;
				break;

			case false:
				ErrorHelper::Set("date_debut", "La date de début est invalide");
				$result = false;
				break;

			default:
				$DemandeConges->StartDate = DateTime::createFromFormat('Y-m-d', $StartDate);
				break;
		}


		switch ($StartDate = $Request->Data->FilterString("type_debut")) {
			case null:
				ErrorHelper::Set("type_debut", "La période de début est obligatoire");
				$result = false;
				break;

			case false:
				ErrorHelper::Set("type_debut", "La période de début est invalide");
				$result = false;
				break;

			case 'morning':
				$DemandeConges->StartMorning = true;
				break;

			case 'afternoon':
				$DemandeConges->StartMorning = false;
				break;
		}


		switch ($EndDate = $Request->Data->FilterString("date_fin")) {
			case null:
				ErrorHelper::Set("date_fin", "La date de fin est obligatoire");
				$result = false;
				break;

			case false:
				ErrorHelper::Set("date_fin", "La date de fin est invalide");
				$result = false;
				break;

			default:
				$DemandeConges->EndDate = DateTime::createFromFormat('Y-m-d', $EndDate);
				break;
		}


		switch ($StartDate = $Request->Data->FilterString("type_debut")) {
			case null:
				ErrorHelper::Set("type_debut", "La période de début est obligatoire");
				$result = false;
				break;

			case false:
				ErrorHelper::Set("type_debut", "La période de début est invalide");
				$result = false;
				break;

			case 'afternoon':
				$DemandeConges->EndAfternoon = true;
				break;

			case 'morning':
				$DemandeConges->EndAfternoon = false;
				break;
		}


		switch ($comment = $Request->Data->FilterString("commentaire")) {
			case null:
				$DemandeConges->comment = '';
				break;

			case false:
				ErrorHelper::Set("commentaire", "Le commentaire est invalide");
				$result = false;
				break;

			default:
				$DemandeConges->comment = $comment;
				break;
		}

		if (!$result)
			return false;

		if ($DemandeConges->StartDate > $DemandeConges->EndDate) {
			ErrorHelper::Set("date", 'La période de congés est invalide, elle finie avant de commencer');
			return false;
		}

		if (
			$DemandeConges->StartDate == $DemandeConges->EndDate 
			&& !$DemandeConges->StartMorning 
			&& !$DemandeConges->EndAfternoon
		) {
			ErrorHelper::Set("date", 'La période de congés est invalide, elle finie avant de commencer');
			return false;
		}
		
		return true;
	}
}