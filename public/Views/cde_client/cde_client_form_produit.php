<?php

use Components\CardComponents\ErrorCardComponent;
use Components\ButtonComponents\ButtonComponent;
use Components\ButtonComponents\SubmitButtonComponent;
use Components\EntityComponents\EntityComponents;
use Components\FormComponents\InputComponents\InputNumberComponent;
use Components\FormComponents\InputComponents\InputTextComponent;
use Components\FormComponents\TextareaComponent;

use Controllers\Actions\ActionsFormCDEClient;
use Controllers\ControllerFormCDEClient;

use Models\Entities\CDEClientL;
use Models\EntityLists\ListFournisseur;


#region validation des variables

if (!isset($errors) || !is_array($errors)) {
	$errors = [
		'errors' => 'Les erreurs ne sont pas définies'
	];
}

if (!isset($CDEClientL) || !($CDEClientL instanceof CDEClientL)) {
	$errors['view_CDEClientL'] = 'Le produit n\'est pas définie ou est invalide';
	$CDEClientL = new CDEClientL;
}

if (!isset($Fournisseurs) || !($Fournisseurs instanceof ListFournisseur)) {
	$errors['view_Fournisseurs'] = 'Les Fournisseurs possibles ne sont pas définies ou sont invalides';
	$Fournisseurs = new ListFournisseur;
}

$action_name = "Ajouter/Modifier";
switch ($action) {
	case ActionsFormCDEClient::CDEClientL_ADD_PROCESS:
		$action_name = "Ajouter";
		break;
	
	case ActionsFormCDEClient::CDEClientL_UPDATE_PROCESS:
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
			<h4 class="text-4xl lg:text-2xl font-medium"><?= $action_name ?> un produit</h4>
		</div>

		<?php ErrorCardComponent::Display('errors', $errors ?? null) ?>
		
		<form id="CDEClientL_form" method="post" class="flex flex-col gap-y-8 lg:gap-y-4" >
			<div>
				<label for="produit" class="block text-gray-700 font-medium mb-2 text-center 
					text-4xl 
					lg:text-lg "
					>Nom du produit</label>
				<?php InputTextComponent::Display(
					"produit", 
					InputTextComponent::DEFAULT_CLASS, 
					"produit", 
					true,
					$CDEClientL->produit
				); ?>
			</div>


			<div>
				<?php EntityComponents::DisplaySelect(
					$Fournisseurs,
					'fournisseur',
					'fournisseur',
					'Fournisseur',
					true,
					false,
					$CDEClientL->id_fournisseur
				); ?>
			</div>


			<div>
				<label for="qte" class="block text-gray-700 font-medium mb-2 text-center 
					text-4xl 
					lg:text-lg "
					>Quantité</label>

				<?php InputNumberComponent::Display(
					"qte", 
					InputNumberComponent::DEFAULT_CLASS, 
					"qte", 
					true, 
					$CDEClientL->qte ?? "",
					0, 
					null, 
					1
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
					$CDEClientL->demandeurComment,
					5
				); ?>
			</div>

			<!-- Bouttons de validation -->
			<div class="flex flex-col gap-4 ">
				<?php SubmitButtonComponent::Display(
					"confirm", 
					SubmitButtonComponent::EMERALD_CLASS, 
					"$action_name un produit", 
					"index.php?controller=".ControllerFormCDEClient::class."&action=".$action
				); ?>

				<?php ButtonComponent::Display(
					"cancel", 
					ButtonComponent::RED_CLASS, 
					"Annuler", 
					"index.php?controller=".ControllerFormCDEClient::class."&action=".ActionsFormCDEClient::DETAILS,
					false,
					"Confirmez l\'annulation"
				); ?>
			</div>
		</form>
	</main>
</body>