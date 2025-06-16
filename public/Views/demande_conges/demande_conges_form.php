<?php
use Components\ButtonComponents\ButtonComponent;
use Components\ButtonComponents\SubmitButtonComponent;
use Components\EntityComponents\EntityComponents;
use Components\FormComponents\InputComponents\InputDateComponent;
use Components\FormComponents\SelectionComponent\SelectComponent;
use Components\FormComponents\TextareaComponent;
use Controllers\Actions\ActionsFormDemandeConges;
use Controllers\ControllerFormDemandeConges;

use Models\Entities\DemandeConges; 
use Components\CardComponents\ErrorCardComponent;
use Models\EntityLists\ListTypeConges;

if (!isset($errors) || !is_array($errors)) {
	$errors = [
		'errors' => 'Les erreurs ne sont pas définies'
	];
}

if (!isset($DemandeConges) || !($DemandeConges instanceof DemandeConges)) {
	$errors['view_DemandeConges'] = 'La demande de conges n\'est pas définie ou est invalide';
	$DemandeConges = new DemandeConges;
}

if (!isset($TypesConges) || !($TypesConges instanceof ListTypeConges)) {
	$errors['view_TypesConges'] = 'Les Types de conges possibles ne sont pas définis ou sont invalides';
	$Destinataires = new ListTypeConges;
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
			<h2 class="text-6xl lg:text-3xl font-medium">DEMANDE DE CONGES</h2>
			<h4 class="text-4xl lg:text-xl font-medium">Créer une demande</h4>
		</div>

		<?php ErrorCardComponent::Display('errors', $errors) ?>

		<form method="post" class="mb-4">
			<div class="mb-10 lg:mb-4">
				<?php EntityComponents::DisplaySelect(
					$TypesConges,
					'type_conges',
					'type_conges',
					'Type de conges',
					true,
					false,
					$DemandeConges->id_type_conges
				); ?>
			</div>


			<!-- Starting date + periode -->
			<div class=" mb-10 lg:mb-4">
				<label for="date_debut" class="block text-gray-700 font-medium mb-2 text-center
					text-4xl 
					lg:text-lg "
				>Date de début</label>

				<div class="
					flex flex-row
					rounded-xl 
					hover:outline-dotted hover:outline-indigo-400 outline-offset-1
				">
					<?php InputDateComponent::Display(
						"date_debut",
						InputDateComponent::DEFAULT_CLASS.' rounded-l-xl rounded-r-none',
						"date_debut",
						true,
						$DemandeConges->StartDate
					); ?>

					<?php SelectComponent::Start(
						'type_debut',
						SelectComponent::DEFAULT_CLASS.' rounded-r-xl rounded-l-none',
						'type_debut',
						true,
					); ?>
						<?php if ($DemandeConges->StartMorning): ?>
							<option value="morning" selected>Matin</option>
							<option value="afternoon">Après midi</option>
						<?php else: ?>
							<option value="morning">Matin</option>
							<option value="afternoon" selected>Après midi</option>
						<?php endif; ?>
					<?php SelectComponent::End(); ?>
				</div>
			</div>


			<!-- Ending date + periode -->
			<div class="mb-10 lg:mb-4">
				<label for="date_fin" class="block text-gray-700 font-medium mb-2 text-center
					text-4xl 
					lg:text-lg "
				>Date de fin</label>

				<div class="
					flex flex-row 
					rounded-xl 
					hover:outline-dotted hover:outline-indigo-400 outline-offset-1 
				">
					<?php InputDateComponent::Display(
						"date_fin",
						InputDateComponent::DEFAULT_CLASS.' rounded-l-xl rounded-r-none',
						"date_fin",
						true,
						$DemandeConges->EndDate
					); ?>

					<?php SelectComponent::Start(
						'type_debut',
						SelectComponent::DEFAULT_CLASS.' rounded-r-xl rounded-l-none',
						'type_debut',
						true,
					); ?>
						<?php if ($DemandeConges->EndAfternoon): ?>
							<option value="morning">Matin</option>
							<option value="afternoon" selected>Après midi</option>
						<?php else: ?>
							<option value="morning" selected>Matin</option>
							<option value="afternoon">Après midi</option>
						<?php endif; ?>
					<?php SelectComponent::End(); ?>
				</div>
			</div>


			<div class="mb-10 lg:mb-4">
				<label for="commentaire" class="block text-gray-700 font-medium mb-2 text-center
					text-4xl 
					lg:text-lg "
					>Commentaire</label>

				<?php TextareaComponent::Display(
					"commentaire",
					TextareaComponent::DEFAULT_CLASS,
					"commentaire",
					false,
					$DemandeConges->comment ?? "",
					5
				); ?>
			</div>

			
			<div class="flex flex-col gap-4 ">
				<?php SubmitButtonComponent::Display(
					"confirm", 
					ButtonComponent::EMERALD_CLASS, 
					"Valider", 
					"index.php?controller=".ControllerFormDemandeConges::class."&action=".ActionsFormDemandeConges::DEMANDE_ADD_PROCESS
				); ?>
			
				<?php ButtonComponent::Display(
					"cancel", 
					ButtonComponent::RED_CLASS, 
					"Annuler", 
					"index.php?controller=".ControllerFormDemandeConges::class."&action=".ActionsFormDemandeConges::CANCEL_PROCESS,
					false,
					"Confirmez l\'annulation"
				); ?>
			</div>
		</form>
	</main>
</body>