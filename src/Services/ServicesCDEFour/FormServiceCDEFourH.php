<?php
namespace Services\ServicesCDEFour;

use Exception;

use Interfaces\FormServiceInterface;
use Models\Entities\CDEFourH;
use Utils\Requests\Request;
use Utils\Session\ErrorHelper;


/**
 * 
 */
class FormServiceCDEFourH implements FormServiceInterface
{
	public function __construct() { }

	/**
	 * Handle the data in the CDEFourH form
	 * @param CDEFourH $CDEFourH
	 * @return bool
	 */
	public function Handle(Request $Request, object $CDEFourH): bool {
		$result = true;

		if ($CDEFourH instanceof CDEFourH === false)
			throw new Exception('Le type de la commande fournisseur est invalide');

		switch ($id_fournisseur = $Request->Data->FilterInt('fournisseur')) {
			case null:
				ErrorHelper::Set('fournisseur', 'Le fournisseur est obligatoire');
				$result = false;
				break;

			case false:
				ErrorHelper::Set('fournisseur', 'Le fournisseur est invalide');
				$result = false;
				break;

			default:
				$CDEFourH->id_fournisseur = $id_fournisseur;
				break;
		}


		switch ($id_destinataire = $Request->Data->FilterInt('destinataire')) {
			case null:
				ErrorHelper::Set('destinataire', 'Le destinataire est obligatoire');
				$result = false;
				break;

			case false:
				ErrorHelper::Set('destinataire', 'Le destinataire est invalide');
				$result = false;
				break;

			default:
				$CDEFourH->id_destinataire = $id_destinataire;
				break;
		}


		switch ($comdem = $Request->Data->FilterString('comdem')) {
			case null:
				$CDEFourH->demandeurComment = '';
				break;

			case false:
				ErrorHelper::Set('comdem', 'Le commentaire demandeur est invalide');
				$result = false;
				break;

			default:
				$CDEFourH->demandeurComment = $comdem;
				break;
		}

		return $result;
	}
}
