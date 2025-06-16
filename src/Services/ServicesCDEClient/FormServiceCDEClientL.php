<?php
namespace Services\ServicesCDEClient;

use Interfaces\FormServiceInterface;
use Models\Entities\CDEClientL;
use Utils\Requests\Request;
use Utils\Session\ErrorHelper;

/**
 * FormServiceCDEClientL
 * 
 * This class is used to handle the form submission for CDEClientL
 * It will validate the data and set the properties of the CDEClientL object
 * 
 * @package Services\ServicesCDEClient
 */
class FormServiceCDEClientL implements FormServiceInterface
{
	public function __construct() { }


	/**
	 * @param Request $Request
	 * @param CDEClientL $CDEClientL
	 * @return bool
	 */
	public function Handle(Request $Request, object $CDEClientL): bool {
		$result = true;

		if ($CDEClientL instanceof CDEClientL === false)
			throw new \Exception("CDEClientL must be an instance of CDEClientL");

		switch ($fournisseur = $Request->Data->FilterInt("fournisseur")) {
			case null:
				ErrorHelper::Set("fournisseur", "Le fournisseur est obligatoire");
				$result = false;
				break;

			case false:
				ErrorHelper::Set("fournisseur", "Le fournisseur est invalide");
				$result = false;
				break;

			default:
				$CDEClientL->id_fournisseur = $fournisseur;
				break;
		}


		switch ($produit = $Request->Data->FilterString("produit")) {
			case null:
				ErrorHelper::Set("produit", "Le nom de produit est obligatoire");
				$result = false;
				break;

			case false:
				ErrorHelper::Set("produit", "Le nom de produit est invalide");
				$result = false;
				break;

			default:
				$CDEClientL->produit = $produit;
				break;
		}


		switch ($qte = $Request->Data->FilterInt("qte")) {
			case null:
				ErrorHelper::Set("qte", "La quantitée est obligatoire");
				$result = false;
				break;

			case false:
				ErrorHelper::Set("qte", "La quantitée est invalide");
				$result = false;
				break;

			default:
				if ($qte <= 0) {
					//* needed because of non cromium browsers
					ErrorHelper::Set("qte", "La quantitée est invalide");
					$result = false;
					break;
				}

				$CDEClientL->qte = $qte;
				break;
		}


		switch ($comdem = $Request->Data->FilterString("comdem")) {
			case null:
				$CDEClientL->demandeurComment = "";
				break;

			case false:
				ErrorHelper::Set("comdem", "Le commentaire demandeur est invalide");
				$result = false;
				break;

			default:
				$CDEClientL->demandeurComment = $comdem;
				break;
		}

		return $result;
	}
}