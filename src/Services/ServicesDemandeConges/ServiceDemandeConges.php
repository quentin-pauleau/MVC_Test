<?php
namespace Services\ServicesDemandeConges;

use Exception;
use Models\ModelDemandeConges;
use Models\ModelTypeConges;
use Models\ModelUser;

use Interfaces\ServiceInterface;
use Models\Entities\DemandeConges;
use Core\FileHandler;
use Core\Session\ErrorHelper;


class ServiceDemandeConges implements ServiceInterface
{
	public const PDF_TEMP_FOLDER = 'temp/demande_conges/';
	public const PDF_EXPORT_FOLDER = 'temp/demande_conges/';

	public function __construct() { }


	public function Find(int $id): DemandeConges {
		$Demand = ModelDemandeConges::GetInstance()->GetById($id);
		
		if ($Demand === null)
			throw new Exception("demande diverse not found with \"id = $id\"");

		return $Demand;
	}


	public function Any(int $id): bool {
		return ModelDemandeConges::GetInstance()->Any($id);
	}


	public function New(DemandeConges $DemandeConges): DemandeConges {
		if (!ModelDemandeConges::GetInstance()->Insert($DemandeConges))
			throw new Exception('Unable to insert the demande conges');

		return $this->Find($DemandeConges->id);
	}


	public function AssociateAllReferences(DemandeConges $DemandeConges): bool {
		try
		{
			if ($DemandeConges->id_demandeur != null)
				$DemandeConges->Demandeur = ModelUser::GetInstance()->GetById($DemandeConges->id_demandeur);
			
			if ($DemandeConges->id_type_conges != null)
				$DemandeConges->TypeConges = ModelTypeConges::GetInstance()->GetById($DemandeConges->id_type_conges);
		}
		catch (Exception $e)
		{
			ErrorHelper::SetDefault("AssociateAllReferences", 'Une erreur est survenue lors de la récupération des informations de la demande diverse');
			ErrorHelper::SetDebug("AssociateAllReferences", $e);
			return false;
		}

		return true;
	}


	public function AssociateDemandeur(DemandeConges $DemandeConges): bool {
		if ($DemandeConges->id_demandeur === null)
			throw new Exception('The demande does not have a demandeur');

		$DemandeConges->Demandeur = ModelUser::GetInstance()->GetById($DemandeConges->id_demandeur);

		if ($DemandeConges->Demandeur === null)
			throw new Exception("The demandeur does not exist with id = $DemandeConges->id_demandeur");

		return true;
	}


	public function AssociateTypeConges(DemandeConges $DemandeConges): bool {
		if ($DemandeConges->id_type_conges === null)
			throw new Exception('The demande does not have a "type congès"');

		$DemandeConges->TypeConges = ModelTypeConges::GetInstance()->GetById($DemandeConges->id_type_conges);

		if ($DemandeConges->TypeConges === null)
			throw new Exception("The demandeur does not exist with id = $DemandeConges->id_type_conges");

		return true;
	}


	public function HandleCancellation(int $id): bool {
		$DemandeConges = $this->Find($id);

		if ($DemandeConges->IsTreated())
			throw new Exception('La demande de congés ne peut pas être annulée car elle n\'est pas en attente');

		if (!ModelDemandeConges::GetInstance()->SetCanceled($id))
			throw new Exception('Une erreur est survenue lors de l\'annulation de la demande de congés');
		
		$DemandeConges = $this->Find($id); //* get the updated version
		
		if (!$this->AssociateAllReferences($DemandeConges))
			throw new Exception('Impossible de récuprérer les informations nécessaires à la création de PDF');
		
		return $this->MakePDF($DemandeConges);
	}


	public function HandleAcceptation(int $id, int $numDirector): bool {
		$DemandeConges = $this->Find($id);

		if ($DemandeConges->IsTreated())
			throw new Exception('La demande de congés ne peut pas être annulée car elle n\'est pas en attente');
		
		//* Accepting the demand
		switch ($numDirector) {
			case 1:
				if (!ModelDemandeConges::GetInstance()->SetAccept1($DemandeConges->id))
					throw new Exception('Une erreur est survenue lors de l\'acceptation de la demande de congés');
				break;
			
			case 2:
				if (!ModelDemandeConges::GetInstance()->SetAccept2($DemandeConges->id))
					throw new Exception('Une erreur est survenue lors de l\'acceptation de la demande de congés');
				break;

			default:
				throw new Exception('Le numéro de directeur est invalide');
		}

		//* Verify if the demand is still pending, if treated make the PDF
		$DemandeConges = $this->Find($id);

		if (!$DemandeConges->IsTreated())
			return true;
		
		if (!$this->AssociateAllReferences($DemandeConges))
			throw new Exception('Impossible de récuprérer les informations nécessaires à la création du PDF');

		return $this->MakePDF($DemandeConges);
	}


	public function HandleDeny(int $id, int $numDirector): bool {
		$DemandeConges = $this->Find($id);

		if ($DemandeConges->IsTreated())
			throw new Exception('La demande de congés ne peut pas être annulée car elle n\'est pas en attente');
		
		//* Accepting the demand
		switch ($numDirector) {
			case 1:
				if (!ModelDemandeConges::GetInstance()->SetDeny1($DemandeConges->id))
					throw new Exception('Une erreur est survenue lors du refus de la demande de congés');
				break;
		
			case 2:
				if (!ModelDemandeConges::GetInstance()->SetDeny2($DemandeConges->id))
					throw new Exception('Une erreur est survenue lors du refus de la demande de congés');
				break;
			
			default:
				throw new Exception('Le numéro de directeur est invalide');
		}

		//* Verify if the demand is still pending, if treated make the PDF
		$DemandeConges = $this->Find($id);

		if (!$DemandeConges->IsTreated())
			return true; // not treated yet, no PDF is generated

		if (!$this->AssociateAllReferences($DemandeConges))
			throw new Exception('Impossible de récuprérer les informations nécessaires à la création du PDF');

		return $this->MakePDF($DemandeConges);
	}

	public function MakePDF(DemandeConges $DemandeConges): bool {
		$ServicePDF = new ServiceDemandeCongesPDF;
		
		$ServicePDF->CreatePDF($DemandeConges);
		
		$ServicePDF->Export(self::PDF_EXPORT_FOLDER);

		//* Create an associated idx file in the destination directory
		FileHandler::CreateAssociatedIdx(
			self::PDF_EXPORT_FOLDER."/$DemandeConges->UUID.pdf", 
			DemandeConges::UUID_FIELDNAME ." = $DemandeConges->UUID"
		);

		return true;
	}
}