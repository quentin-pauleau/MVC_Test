<?php
use Components\ButtonComponents\ButtonComponent;
use Components\ButtonComponents\SubmitButtonComponent;
use Components\EntityComponents\EntityComponents;
use Components\FormComponents\TextareaComponent;

use Controllers\Actions\ActionsFormCDEFournisseur;
use Controllers\ControllerFormCDEFournisseur;
use Controllers\Actions\ActionsMenu;
use Controllers\ControllerMenu;

use Models\Entities\CDEFourH;
use Components\CardComponents\ErrorCardComponent;
use Models\EntityLists\ListFournisseur;
use Models\EntityLists\ListUser;

if (!isset($errors) || !is_array($errors)) {
	$errors = [
		'errors' => 'Les erreurs ne sont pas définies'
	];
}

if (!isset($CDEFourH) || !($CDEFourH instanceof CDEFourH)) {
	$errors['view_CDEFourh'] = 'La commande stock n\'est pas définie ou est invalide';
	$CDEFourH = new CDEFourH;
}

if (!isset($Destinataires) || !($Destinataires instanceof ListUser)) {
	$errors['view_Destinataires'] = 'Le Destinataires possibles ne sont pas définies ou sont invalides';
	$Destinataires = new ListUser;
}

if (!isset($Fournisseurs) || !($Fournisseurs instanceof ListFournisseur)) {
	$errors['view_Fournisseurs'] = 'Les Fournisseurs possibles ne sont pas définies ou sont invalides';
	$Fournisseurs = new ListFournisseur;
}

$action_name = "Créer/Modifier";
switch ($action) {
	case ActionsFormCDEFournisseur::CDEFourH_ADD_PROCESS:
		$action_name = "Créer";
		break;
	
	case ActionsFormCDEFournisseur::CDEFourH_UPDATE_PROCESS:
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
			<h2 class="text-6xl lg:text-4xl font-medium">COMMANDE STOCK</h2>
			<h4 class="text-4xl lg:text-2xl font-medium"><?= $action_name ?> une demande</h4>
		</div>

		<?php ErrorCardComponent::Display('errors', $errors) ?>

		<form method="post" class="flex flex-col gap-y-8 lg:gap-y-4">
			<?php EntityComponents::DisplaySelect(
				$Fournisseurs,
				'fournisseur',
				'fournisseur',
				'Fournisseur',
				true,
				false,
				$CDEFourH->id_fournisseur
			); ?>
			
			<?php EntityComponents::DisplaySelect(
				$Destinataires,
				'destinataire',
				'destinataire',
				'Destinataire',
				true,
				false,
				$CDEFourH->id_destinataire
			); ?>


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
					$CDEFourH->demandeurComment ?? "",
					5
				); ?>
			</div>
			
			<div class="flex flex-col gap-4 ">
				<?php SubmitButtonComponent::Display(
					"confirm", 
					ButtonComponent::EMERALD_CLASS, 
					"Étape suivante", 
					"index.php?controller=".ControllerFormCDEFournisseur::class."&action=".$action
				); ?>
			
				<?php if ($action === ActionsFormCDEFournisseur::CDEFourH_ADD_PROCESS): ?>
					<?php ButtonComponent::Display(
						"cancel", 
						ButtonComponent::RED_CLASS, 
						"Annuler", 
						"index.php?controller=".ControllerMenu::class."&action=".ActionsMenu::MAIN,
						false,
						true
					); ?>

				<?php elseif ($action === ActionsFormCDEFournisseur::CDEFourH_UPDATE_PROCESS): ?>
					<?php ButtonComponent::Display(
						"cancel", 
						ButtonComponent::RED_CLASS, 
						"Annuler", 
						"index.php?controller=".ControllerFormCDEFournisseur::class."&action=".ActionsFormCDEFournisseur::DETAILS,
						false, 
						"Confirmez l\'annulation"
					); ?>
				<?php endif; ?>
			</div>
		</form>
	</main>
</body>