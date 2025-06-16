
<?php
use Components\ButtonComponents\ButtonComponent;
use Components\ButtonComponents\SubmitButtonComponent;
use Components\FormComponents\InputComponents\InputNumberComponent;
use Components\FormComponents\InputComponents\InputTextComponent;

use Controllers\Actions\ActionsFormCDEFournisseur;
use Controllers\ControllerFormCDEFournisseur;

use Models\Entities\CDEFourL;
use Components\CardComponents\ErrorCardComponent;


#region validation des variables

if (!isset($errors) || !is_array($errors)) {
	$errors = [
		'errors' => 'Les erreurs ne sont pas définies'
	];
}

if (!isset($CDEFourL) || !($CDEFourL instanceof CDEFourL)) {
	$errors['view_CDEFourL'] = 'Le produit n\'est pas définie ou est invalide';
	$CDEFourL = new CDEFourL;
}

$action_name = "Ajouter/Modifier";
switch ($action) {
	case ActionsFormCDEFournisseur::CDEFourL_ADD_PROCESS:
		$action_name = "Ajouter";
		break;
	
	case ActionsFormCDEFournisseur::CDEFourL_UPDATE_PROCESS:
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
			<!-- //TODO: Change to an header -->
			<h2 class="text-6xl lg:text-4xl font-medium">COMMANDE STOCK</h2>
			<h4 class="text-4xl lg:text-2xl font-medium"><?= $action_name ?> un produit</h4>
		</div>

		<?php ErrorCardComponent::Display('errors', $errors) ?>
		
		<form id="CDEFourL_form" method="post" class="flex flex-col gap-y-8 lg:gap-y-4">
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
					$CDEFourL->produit
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
					$CDEFourL->qte ?? "",
					0, 
					null, 
					1
				); ?>
			</div>

			<!-- Bouttons de validation -->
			<div class="flex flex-col gap-4 ">
				<?php SubmitButtonComponent::Display(
					"confirm", 
					SubmitButtonComponent::EMERALD_CLASS, 
					"$action_name un produit", 
					"index.php?controller=".ControllerFormCDEFournisseur::class."&action=".$action
				); ?>

				<?php ButtonComponent::Display(
					"cancel", 
					ButtonComponent::RED_CLASS, 
					"Annuler", 
					"index.php?controller=".ControllerFormCDEFournisseur::class."&action=".ActionsFormCDEFournisseur::DETAILS,
					false, 
					"Confirmez l\'annulation"
				); ?>
			</div>
		</form>
	</main>
</body>