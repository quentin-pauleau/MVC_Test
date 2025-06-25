<?php
namespace Services\ServicesDemandeDiverse;

use Interfaces\FormServiceInterface;
use Models\Entities\DemandeDiverse;
use Core\Requests\Request;
use Core\Session\ErrorHelper;

class FormServiceDemandeDiverse implements FormServiceInterface
{
	public function __construct() { }

	public function Handle(Request $Request, object $DemandeDiverse): bool {
		$result = true;

		if ($DemandeDiverse instanceof DemandeDiverse === false)
			throw new \Exception('DemandeDiverse must be an instance of DemandeDiverse');

		switch ($id_destinataire = $Request->Data->FilterInt("destinataire")) {
			// filter_input(INPUT_POST, "destinataire", FILTER_VALIDATE_INT)
			case null:
				ErrorHelper::Set("destinataire", "Le destinataire est obligatoire");
				$result = false;
				break;

			case false:
				ErrorHelper::Set("destinataire", "Le destinataire est invalide");
				$result = false;
				break;

			default:
				$DemandeDiverse->id_destinataire = $id_destinataire;
				break;
		}


		switch ($id_urgence = $Request->Data->FilterInt("urgence")) {
			// filter_input(INPUT_POST, "urgence", FILTER_VALIDATE_INT)
			case null:
				ErrorHelper::Set("urgence", "L'urgence est obligatoire");
				$result = false;
				break;

			case false:
				ErrorHelper::Set("urgence", "L'urgence est invalide");
				$result = false;
				break;

			default:
				$DemandeDiverse->id_urgence = $id_urgence;
				break;
		}


		switch ($demande = $Request->Data->FilterString("demande")) {
			// filter_input(INPUT_POST, "demande", FILTER_DEFAULT, FILTER_FLAG_NO_ENCODE_QUOTES)
			case null:
				ErrorHelper::Set("demande", "La demande est obligatoire");
				$result = false;
				break;

			case false:
				ErrorHelper::Set("demande", "La demande est invalide");
				$result = false;
				break;

			default:
				$DemandeDiverse->demande = $demande;
				break;
		}
		
		return true;
	}
}