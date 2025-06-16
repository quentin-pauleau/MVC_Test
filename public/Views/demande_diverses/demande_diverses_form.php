<?php
use Components\ButtonComponents\ButtonComponent;
use Components\ButtonComponents\SubmitButtonComponent;
use Components\EntityComponents\EntityComponents;
use Components\FormComponents\SelectionComponent\SelectComponent;
use Components\FormComponents\InputComponents\InputImageComponent;
use Components\FormComponents\TextareaComponent;
use Controllers\Actions\ActionsFormDemandeDiverse;
use Controllers\ControllerFormDemandeDiverse;

use Models\Entities\DegresUrgence;
use Models\Entities\DemandeDiverse; 
use Components\CardComponents\ErrorCardComponent;
use Models\EntityLists\ListDegresUrgence;
use Models\EntityLists\ListUser;

if (!isset($errors) || !is_array($errors)) {
	$errors = [
		'errors' => 'Les erreurs ne sont pas définies'
	];
}

if (!isset($DemandeDiverse) || !($DemandeDiverse instanceof DemandeDiverse)) {
	$errors['view_DemandeDiverse'] = 'La demande diverse n\'est pas définie ou est invalide';
	$DemandeDiverse = new DemandeDiverse;
}

if (!isset($Destinataires) || !($Destinataires instanceof ListUser)) {
	$errors['view_Destinataires'] = 'Les Destinataires possibles ne sont pas définis ou sont invalides';
	$Destinataires = new ListUser;
}

if (!isset($Urgences) || !($Urgences instanceof ListDegresUrgence)) {
	$errors['view_Urgences'] = 'Les Degres d\'Urgences possibles ne sont pas définis ou sont invalides';
	$Urgences = new ListDegresUrgence;
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
			<h2 class="text-6xl lg:text-3xl font-medium">DEMANDE DIVERSES</h2>
			<h4 class="text-4xl lg:text-xl font-medium">Créer une demande</h4>
		</div>

		<?php ErrorCardComponent::Display('errors', $errors) ?>

		<form method="post" 
			enctype="multipart/form-data" 
			class="mb-4"
		>
			<div class="mb-10 lg:mb-4">
				<?php EntityComponents::DisplaySelect(
					$Destinataires,
					'destinataire',
					'destinataire',
					'Destinataire',
					true,
					false,
					$DemandeDiverse->id_destinataire
				); ?>
			</div>
			
			
			<div class="mb-10 lg:mb-4">
				<?php EntityComponents::DisplaySelect(
					$Urgences,
					'urgence',
					'urgence',
					'Degres d\'urgence',
					true,
					false,
					$DemandeDiverse->id_urgence ?? DegresUrgence::DEFAULT_ID
				); ?>
			</div>


			<div class="mb-10 lg:mb-4">
				<label for="demande" class="block text-gray-700 font-medium mb-2 text-center
					text-4xl 
					lg:text-lg "
					>Demande</label>

				<?php TextareaComponent::Display(
					"demande",
					TextareaComponent::DEFAULT_CLASS,
					"demande",
					true,
					$DemandeDiverse->demande ?? "",
					5
				); ?>
			</div>

			<div class="mb-10 lg:mb-4">
				<label for="name" class="block text-gray-700 font-medium mb-2 text-center
					text-4xl 
					lg:text-lg "
				>Joindre une photo</label>

				<?php InputImageComponent::Display(
					"join_images",
					InputImageComponent::DEFAULT_CLASS,
					"join_images",
					false,
					INputimageComponent::CAPTURE_ANY,
					true
				); ?>
			</div>

			
			<div class="flex flex-col gap-4 ">
				<?php SubmitButtonComponent::Display(
					"confirm", 
					ButtonComponent::EMERALD_CLASS, 
					"Valider", 
					"index.php?controller=".ControllerFormDemandeDiverse::class."&action=".ActionsFormDemandeDiverse::DEMANDE_ADD_PROCESS
				); ?>
			
				<?php ButtonComponent::Display(
					"cancel", 
					ButtonComponent::RED_CLASS, 
					"Annuler", 
					"index.php?controller=".ControllerFormDemandeDiverse::class."&action=".ActionsFormDemandeDiverse::CANCEL_PROCESS,
					false,
					"Confirmez l\'annulation"
				); ?>
			</div>
		</form>
	</main>
</body>