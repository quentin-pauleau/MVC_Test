<?php
use Components\ButtonComponents\ButtonComponent;
use Components\ButtonComponents\SubmitButtonComponent;
use Components\EntityComponents\EntityComponents;
use Components\FormComponents\RadioComponent;
use Components\FormComponents\InputComponents\InputTextComponent;
use Components\FormComponents\TextareaComponent;

use Controllers\Actions\ActionsFormCDEClient;
use Controllers\ControllerFormCDEClient;
use Controllers\Actions\ActionsMenu;
use Controllers\ControllerMenu;

use Models\Entities\CDEClientH;
use Components\CardComponents\ErrorCardComponent;
use Models\Entities\DegresUrgence;
use Models\EntityLists\ListDegresUrgence;
use Models\EntityLists\ListTypeLivraison;
use Models\EntityLists\ListUser;

if (!isset($errors) || !is_array($errors)) {
	$errors = [
		'errors' => 'Les erreurs ne sont pas définies'
	];
}

if (!isset($CDEClientH) || !($CDEClientH instanceof CDEClientH)) {
	$errors['view_CDEClientH'] = 'La demande client n\'est pas définie ou est invalide';
	$CDEClientH = new CDEClientH();
}

if (!isset($Destinataires) || !($Destinataires instanceof ListUser)) {
	$errors['view_Destinataires'] = 'Les Destinataires possibles ne sont pas définies ou sont invalides';
	$Destinataires = new ListUser;
}

if (!isset($TypesLivraison) || !($TypesLivraison instanceof ListTypeLivraison)) {
	$errors['view_TypesLivraison'] = 'Les Types de Livraison possibles ne sont pas définies ou sont invalides';
	$TypesLivraison = new ListTypeLivraison;
}

if (!isset($Urgences) || !($Urgences instanceof ListDegresUrgence)) {
	$errors['view_Urgences'] = 'Les Degres d\'Urgences possibles ne sont pas définies ou sont invalides';
	$Urgences = new ListDegresUrgence;
}

$action_name = "Créer/Modifier";

switch ($action) {
	case ActionsFormCDEClient::CDEClientH_ADD_PROCESS:
		$action_name = "Créer";
		break;
	
	case ActionsFormCDEClient::CDEClientH_UPDATE_PROCESS:
		$action_name = "Modifier";
		break;
}
?>

<?php require "public/Views/head.php"; ?>

<body class="mx-auto my-8 
		bg-orange-300 
		max-w-6xl 
		lg:max-w-2xl 
">
	<main class="flex flex-col px-8 py-6 gap-y-8 
		lg:bg-orange-200 lg:rounded-lg
		lg:border-1 lg:border-orange-400 
		lg:shadow-sm lg:shadow-orange-500
	">
		<div class="flex flex-col items-center mx-auto gap-y-2">
			<h2 class="text-6xl lg:text-4xl font-medium">COMMANDE CLIENT</h2>
			<h4 class="text-4xl lg:text-2xl font-medium"><?= $action_name ?> une demande</h4>
		</div>

		<?php ErrorCardComponent::Display('errors', $errors) ?>

		<form method="post" class="flex flex-col gap-y-8 lg:gap-y-4 " >
			<div>
				<label for="name" class="block text-gray-700 font-medium mb-2 text-center
					text-4xl 
					lg:text-lg "
				>Nom du client</label>

				<?php InputTextComponent::Display(
					"name", 
					InputTextComponent::DEFAULT_CLASS,
					"name",
					true,
					$CDEClientH->nom_client ?? ""
				); ?>
			</div>
			
			<div>
				<?php EntityComponents::DisplaySelect(
					$TypesLivraison,
					'type_livraison',
					'type_livraison',
					'Type de Livraison',
					true,
					false,
					$CDEClientH->id_type_livraison
				); ?>
			</div>


			<div class="flex flex-wrap lg:mx-2 ">
				<div class="w-1/2 px-1">
					<?php RadioComponent::Display(
						"cde", 
						RadioComponent::DEFAULT_CLASS, 
						"cde_devis", 
						true, 
						$CDEClientH->cde ?? false, 
						"cde", 
						"Commande"
					); ?>
				</div>

				<div class="w-1/2 px-1">
					<?php RadioComponent::Display(
						"devis", 
						RadioComponent::DEFAULT_CLASS, 
						"cde_devis", 
						true, 
						$CDEClientH->devis ?? false, 
						"devis", 
						"Devis"
					); ?>
				</div>
			</div>
			

			<div>
				<?php EntityComponents::DisplaySelect(
					$Destinataires,
					'destinataire',
					'destinataire',
					'Destinataire',
					true,
					false,
					$CDEClientH->id_destinataire
				); ?>
			</div>


			<div>
				<?php EntityComponents::DisplaySelect(
					$Urgences,
					'urgence',
					'urgence',
					'Degres d\'urgence',
					true,
					false,
					$CDEClientH->id_urgence ?? DegresUrgence::DEFAULT_ID
				); ?>
			</div>
			
			<div>
				<label for="comdem" class="block text-gray-700 font-medium mb-2 text-center 
					text-4xl 
					lg:text-lg "
					>Commentaire demandeur</label>

				<?php TextareaComponent::Display(
					"comdem",
					TextareaComponent::DEFAULT_CLASS,
					"comdem",
					false,
					$CDEClientH->demandeurComment ?? "",
					5
				); ?>
			</div>

			
			<div class="flex flex-col gap-4 ">
				<?php SubmitButtonComponent::Display(
					"confirm", 
					ButtonComponent::EMERALD_CLASS, 
					"Étape suivante", 
					"index.php?controller=".ControllerFormCDEClient::class."&action=".$action
				); ?>
			
				<?php if ($action === ActionsFormCDEClient::CDEClientH_ADD_PROCESS): ?>
					<?php ButtonComponent::Display(
						"cancel", 
						ButtonComponent::RED_CLASS, 
						"Annuler", 
						"index.php?controller=".ControllerMenu::class."&action=".ActionsMenu::MAIN,
						false, 
						true
					); ?>

				<?php elseif ($action === ActionsFormCDEClient::CDEClientH_UPDATE_PROCESS): ?>
					<?php ButtonComponent::Display(
						"cancel2", 
						ButtonComponent::RED_CLASS, 
						"Annuler", 
						"index.php?controller=".ControllerFormCDEClient::class."&action=".ActionsFormCDEClient::DETAILS,
						false, 
						"Confirmez l\'annulation"
					); ?>
				<?php endif; ?>
			</div>
		</form>
	</main>
</body>