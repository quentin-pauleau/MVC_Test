<?php
namespace Services\ServicesCDEClient;

use Exception;
use Interfaces\FormServiceInterface;
use Models\Entities\CDEClientH;
use Core\Requests\Request;
use Core\Session\ErrorHelper;


class FormServiceCDEClientH implements FormServiceInterface
{
	public function __construct() {}
	

	/**
	 * Handle the data in the CDEClientH form
	 * @param Request $Request
	 * @param CDEClientH $CDEClientH
	 * @return bool
	 */
	public function Handle(Request $Request, object $CDEClientH): bool {
		$result = true;

		if ($CDEClientH instanceof CDEClientH === false)
			throw new Exception("The CDEClientH is not an instance of CDEClientH");

		//* Handle the form data
		switch ($nom_client = $Request->Data->FilterString("name")) {
			case null:
				ErrorHelper::Set("name", "Le nom du client est obligatoire");
				$result = false;
				break;

			case false:
				ErrorHelper::Set("name", "Le nom du client est invalide");
				$result = false;
				break;

			default:
				$CDEClientH->nom_client = $nom_client;
				break;
		}


		switch ($type_livraison = $Request->Data->FilterInt("type_livraison")) {
			case null:
				ErrorHelper::Set("type_livraison", "Le type de livraison est obligatoire");
				$result = false;
				break;

			case false:
				ErrorHelper::Set("type_livraison", "Le type de livraison est invalide");
				$result = false;
				break;

			default:
				$CDEClientH->id_type_livraison = $type_livraison;
				break;
		}


		switch ($id_destinataire = $Request->Data->FilterInt("destinataire")) {
			case null:
				ErrorHelper::Set("destinataire", "Le destinataire est obligatoire");
				$result = false;
				break;

			case false:
				ErrorHelper::Set("destinataire", "Le destinataire est invalide");
				$result = false;
				break;

			default:
				$CDEClientH->id_destinataire = $id_destinataire;
				break;
		}


		switch ($id_urgence = $Request->Data->FilterInt("urgence")) {
			case null:
				ErrorHelper::Set("urgence", "L'urgence est obligatoire");
				$result = false;
				break;

			case false:
				ErrorHelper::Set("urgence", "L'urgence est invalide");
				$result = false;
				break;

			default:
				$CDEClientH->id_urgence = $id_urgence;
				break;
		}


		switch ($Request->Data->FilterString("cde_devis")) {
			case null:
				ErrorHelper::Set("cde_devis", "Commande ou devis est obligatoire");
				$result = false;
				break;

			case false:
				ErrorHelper::Set("cde_devis", "Commande ou devis est invalide");
				$result = false;
				break;

			case "cde":
				$CDEClientH->cde = true;
				$CDEClientH->devis = false;
				break;

			case "devis":
				$CDEClientH->cde = false;
				$CDEClientH->devis = true;
				break;
		}


		switch ($comdem = $Request->Data->FilterString("comdem")) {
			case null:
				$CDEClientH->demandeurComment = $comdem;
				break;

			case false:
				ErrorHelper::Set("comdem", "Le commentaire demandeur est invalide");
				$result = false;
				break;

			default:
				$CDEClientH->demandeurComment = $comdem;
				break;
		}

		return $result;
	}
}