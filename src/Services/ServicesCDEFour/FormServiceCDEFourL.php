<?php
namespace Services\ServicesCDEFour;

use Exception;

use Interfaces\FormServiceInterface;
use Models\Entities\CDEFourL;
use Core\Requests\Request;
use Core\Session\ErrorHelper;


/**
 * 
 */
class FormServiceCDEFourL implements FormServiceInterface
{
	public function __construct() { }
	/**
	 * @param CDEFourL $CDEFourL
	 * @return bool
	 */
	public function Handle(Request $Request, object $CDEFourL): bool {
		$result = true;

		if (!($CDEFourL instanceof CDEFourL))
			throw new Exception('CDEFourL must be an instance of CDEFourL');

		switch ($produit = $Request->Data->FilterString('produit')) {
			case false:
				ErrorHelper::Set("produit", "Le nom de produit est invalide");
				$result = false;
				break;

			case null:
				ErrorHelper::Set("produit", "Le nom de produit est obligatoire");
				$result = false;
				break;
			default:
				$CDEFourL->produit = $produit;
				break;
		}


		switch ($qte = $Request->Data->FilterInt('qte')) {
			case false:
				ErrorHelper::Set("qte", "La quantitée est invalide");
				$result = false;
				break;
			
			case null:
				ErrorHelper::Set("qte", "La quantitée est obligatoire");
				$result = false;
				break;

			default:
				if ($qte <= 0) {
					//* needed because of non cromium browsers
					ErrorHelper::Set("qte", "La quantitée est invalide");
					$result = false;
					break;
				}
				$CDEFourL->qte = $qte;
				break;
		}

		return $result;
	}
}
