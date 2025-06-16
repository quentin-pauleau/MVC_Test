<?php

use Components\ButtonComponents\ButtonComponent;
use Components\CardComponents\ErrorCardComponent;
use Components\EntityComponents\DemandeCongesComponents;
use Components\EntityComponents\EntityComponents;
use Components\FormComponents\CheckboxComponent;
use Components\FormComponents\InputComponents\InputDateComponent;
use Components\FormComponents\SelectionComponent\SelectComponent;
use Controllers\Actions\ActionsMenu;
use Controllers\ControllerMenu;
use Enums\ComparaisonOpperators;
use Enums\SuivisViewTypes;
use Utils\Session\UserHelper;

if (!isset($errors) || !is_array($errors)) {

}


if (!isset($view)) {
	$view = SuivisViewTypes::DEMANDEUR;
}

if (!isset($NumDirector) || !is_int($NumDirector)) {
	$NumDirector = null;
}

if (!isset($DefaultStartDate) || !($DefaultStartDate instanceof DateTime)) {
	$DefaultStartDate = null;
}

if (!isset($DefaultEndDate) || !($DefaultEndDate instanceof DateTime)) {
	$DefaultEndDate = null;
}

?>


<?php require "public/Views/head.php"; ?>

<body class="mx-auto 
	bg-orange-300 
	max-w-6xl 
	lg:max-w-4xl 
">
	<header class="
		sticky z-40
		mb-6 top-0 right-0 left-0 px-8 py-6 gap-x-8 
		flex items-center
		bg-gray-100 border-b-4 border-orange-200 shadow 
		w-full h-auto 
	">
		<div class="w-1/5 flex justify-center">
			<?php ButtonComponent::Display(
				'exit_list',
				ButtonComponent::RED_CLASS."",
				'Retour',
				'index.php?controller='.ControllerMenu::class.'&action='.ActionsMenu::class
			); ?>
		</div>

		<div class="w-3/5 flex justify-center">
			<h1 class="text-4xl lg:text-4xl text-center">
				<?= $title ?? 'Mes Demandes de Congés' ?>
			</h1>
		</div>

		<div class="w-1/5 flex justify-center">
		</div>
	</header>

	<main class="flex flex-col px-8 py-6 gap-y-8 
		bg-orange-200 rounded-lg
		border-1 border-orange-400 
		shadow-sm shadow-orange-500

		lg:bg-orange-200 lg:rounded-lg
		lg:border-1 lg:border-orange-400 
		lg:shadow-sm lg:shadow-orange-500
	">
		<section>
			<?php ErrorCardComponent::Display('errors', $errors); ?>
		</section>

		<!-- Demande conges -->
		<section class="flex flex-col items-center 
			bg-gray-100 border rounded-lg px-4 py-6 shadow
			max-w-6xl 
			lg:max-w-4xl
		">
			<!-- Content of the summary -->
			<span id="demande_conges_count" class="text-2xl">
				<!-- Async Call -->
			</span>
			
			<!-- Filters -->
			<div class="flex flex-col items-center gap-y-8 lg:gap-y-4">
				<div class="w-4/5">
					<?php if ($is_demandeur): ?>
						<input class="hidden" id="demande_conges_demandeur" value="<?= $defaultDemandeurId ?? UserHelper::GetUserId() ?>" hidden/>
					<?php else: ?>
						<?php EntityComponents::DisplaySelect(
							$Demandeurs,
							'demande_conges_demandeur',
							'demande_conges_demandeur',
							'Demandeur',
							false,
							false,
							$defaultDemandeurId ?? null
						); ?>
					<?php endif; ?>
				</div>
				
				<div>
					<h4 class="text-4xl lg:text-lg text-center block text-gray-700 font-medium ">
						Type de conges
					</h4>

					<div class="flex flex-wrap lg:mx-2 items-center justify-center ">
						<?php foreach ($TypesConges as $key => $TypeConges): ?>
							<?php CheckboxComponent::Display(
								"demande_conges_type_$key", 
								CheckboxComponent::DEFAULT_CLASS, 
								'demande_conges_type', 
								true, 
								true, // all are selected by default
								$TypeConges->id, 
								$TypeConges
							); ?>
						<?php endforeach; ?>
					</div>
				</div>
				

				<div class="">
					<label for="date_debut" class="block text-gray-700 font-medium mb-2 text-center
						text-4xl 
						lg:text-lg "
					>Date de début</label>

					<div class="
						flex flex-row 
						rounded-xl 
						hover:outline-dotted hover:outline-indigo-400 outline-offset-1
					">
						<?php SelectComponent::Start(
							'demande_conges_type_debut',
							'w-full rounded-lg 
								p-8 text-5xl 
								lg:p-2 lg:text-base
								
								border-4 outline-none outline-offset-2 
								lg:border-2 lg:p-2 
								hover:outline-indigo-400 hover:outline-2 hover:bg-indigo-100
								focus:border-blue-400 focus:bg-blue-50 focus:hover:outline-none 
								disabled:hover:outline-none disabled:bg-gray-200
								
								rounded-l-xl rounded-r-none 
								hover:z-10 
								border-red-400 bg-red-50
								',
							'demande_conges_type_debut',
							true,
						); ?>
							<option value="" selected></option>

							<option value="<?= ComparaisonOpperators::EQUALS ?>">=</option>
							<option value="<?= ComparaisonOpperators::DIFFERENT ?>">≠</option>

							<option value="<?= ComparaisonOpperators::GREATER_THAN ?>">></option>
							<option value="<?= ComparaisonOpperators::GREATER_THAN_EQUALS ?>" 
								<?php if ($DefaultStartDate !== null): ?> selected <?php endif ?>
							>≥</option>

							<option value="<?= ComparaisonOpperators::LESS_THAN ?>"><</option>
							<option value="<?= ComparaisonOpperators::LESS_THAN_EQUALS ?>">≤</option>
						<?php SelectComponent::End(); ?>

						<?php InputDateComponent::Display(
							"demande_conges_date_debut",
							InputDateComponent::DEFAULT_CLASS.'hover:z-10 rounded-r-xl rounded-l-none',
							"demande_conges_date_debut",
							true
						); ?>
					</div>
				</div>

				<div>
					<label for="date_debut" class="block text-gray-700 font-medium mb-2 text-center
						text-4xl 
						lg:text-lg "
					>Date de Fin</label>

					<div class="
						flex flex-row
						rounded-xl 
						hover:outline-dotted hover:outline-indigo-400 outline-offset-1
					">
						<?php SelectComponent::Start(
							'demande_conges_type_end',
							'w-full rounded-lg 
								p-8 text-5xl 
								lg:p-2 lg:text-base
								
								border-4 outline-none outline-offset-2 
								lg:border-2 lg:p-2 
								hover:outline-indigo-400 hover:outline-2 hover:bg-indigo-100
								focus:border-blue-400 focus:bg-blue-50 focus:hover:outline-none 
								disabled:hover:outline-none disabled:bg-gray-200
								
								rounded-l-xl rounded-r-none 
								hover:z-10 

								'.($DefaultEndDate === null ? 'border-red-400 bg-red-50' : 'border-emerald-400 bg-emerald-50'),
							'demande_conges_type_end',
							true,
						); ?>
							<option value="" selected></option>

							<option value="<?= ComparaisonOpperators::EQUALS ?>">=</option>
							<option value="<?= ComparaisonOpperators::DIFFERENT ?>">≠</option>

							<option value="<?= ComparaisonOpperators::GREATER_THAN ?>">></option>
							<option value="<?= ComparaisonOpperators::GREATER_THAN_EQUALS ?>" 
								<?php if ($DefaultEndDate !== null): ?> selected <?php endif ?>
							>≥</option>

							<option value="<?= ComparaisonOpperators::LESS_THAN ?>"><</option>
							<option value="<?= ComparaisonOpperators::LESS_THAN_EQUALS ?>">≤</option>
						<?php SelectComponent::End(); ?>

						<?php InputDateComponent::Display(
							"demande_conges_date_end",
							InputDateComponent::DEFAULT_CLASS.' rounded-r-xl rounded-l-none',
							"demande_conges_date_end",
							true,
							$DefaultEndDate ?? null
						); ?>
					</div>
				</div>
				
				<div>
					<h4 class="text-4xl lg:text-lg text-center block text-gray-700 font-medium ">
						Etats
					</h4>

					<?php DemandeCongesComponents::DisplayStatesSelect(
						'demande_conges_state',
						'demande_conges_state',
						$DemandeCongesStates,
						$DemandeCongesStates,
						$DemandeCongesStates
					) ?>
				</div>
				
				<?php if ($NumDirector !== null): ?>
					<div>
						<?php CheckboxComponent::Display(
							'demande_conges_is_treated',
							CheckboxComponent::DEFAULT_CLASS,
							'demande_conges_is_treated',
							true,
							false,
							'no',
							'Traitée'
						); ?>

						<?php CheckboxComponent::Display(
							'demande_conges_is_treated',
							CheckboxComponent::DEFAULT_CLASS,
							'demande_conges_is_treated',
							true,
							false,
							'yes',
							'Traitée'
						); ?>
					</div>
				<?php endif ?>
			</div>
		</section>

		<section class="flex flex-col items-center 
			bg-gray-100 border rounded-lg px-4 py-6 shadow
			max-w-6xl 
			lg:max-w-4xl
		">
			<!-- Table -->
			<div id="demande_conges_table" class="w-full">
				<!-- Async Call -->
			</div>
		</section>
	</main>

	
</body>




<script>
	//* load tables with default values and set the onchange events
	document.addEventListener("DOMContentLoaded", () => {
		LoadDemandeConges();
	});

	document.getElementById('demande_conges_demandeur').onchange = LoadDemandeConges;

	document.getElementById('demande_conges_date_debut').onchange = LoadDemandeConges;

	document.getElementById('demande_conges_type_debut').onchange = DateOpperatorHandleing;

	document.getElementById('demande_conges_date_end').onchange = LoadDemandeConges;
	
	document.getElementById('demande_conges_type_end').onchange = DateOpperatorHandleing;

	document.querySelectorAll(`input[name="demande_conges_type"]`).forEach(element => {
		element.onchange = LoadDemandeConges;
	});

	document.querySelectorAll(`input[name="demande_conges_state"]`).forEach(element => {
		element.onchange = LoadDemandeConges;
	});


	//*...
	
	async function LoadDemandeConges() {
		const VIEW = "<?= $view ?>";

		const ID_DEMANDEUR = document.getElementById('demande_conges_demandeur').value;

		const START_DATE = document.getElementById('demande_conges_date_debut').value;
		const START_DATE_OPPERATOR = document.getElementById('demande_conges_type_debut').value;

		const END_DATE = document.getElementById('demande_conges_date_end').value;
		const END_DATE_OPPERATOR = document.getElementById('demande_conges_type_end').value;

		const ID_TYPE = GetCheckedValues('demande_conges_type').join('|');
		const ID_STATE = GetCheckedValues('demande_conges_state').join('|');

		//* 
		const TABLE_API_URL = `src/ServicesAsync/DemandeCongesAsync/AsyncDemandeCongesTable.php?view=${VIEW}&id_type=${ID_TYPE}&id_state=${ID_STATE}&id_demandeur=${ID_DEMANDEUR}&start_date=${START_DATE}&start_type=${START_DATE_OPPERATOR}&end_date=${END_DATE}&end_type=${END_DATE_OPPERATOR}`;
		
		const TABLE_DIV = document.getElementById('demande_conges_table');
		const COUNT_DIV = document.getElementById('demande_conges_count');

		if (TABLE_DIV == null || COUNT_DIV == null) {
			throw new Error('Can\'t find the demande_conges_table div or demande_conges_count div');
		}

		const TABLE = await GetHTML(TABLE_API_URL);

		TABLE_DIV.innerHTML = TABLE;

		const COUNT = document.querySelectorAll('[id^="DemandeConges_"]').length;

		switch (COUNT) {
			case 0:
				COUNT_DIV.innerHTML = 'Aucune Demande Conges';
				break;
		
			default:
				COUNT_DIV.innerHTML = `${COUNT} Demande Conges`;
				break;
		}
	}

	
	//* Get the html from a given url
	async function GetHTML(url) {
		const response = await fetch(url, {
			method: "GET",
		});

		if (!response.ok) {
			throw new Error(`Response status: ${response.status}`);
		}

		return response.text();
	}


	//*
	
	function GetCheckedValues(name) {
		const checkboxes = document.querySelectorAll(`input[name="${name}"]:checked`);
		return Array.from(checkboxes).map(cb => cb.value);
	}

	function DateOpperatorHandleing () {
		if (this.value == '') {
			this.classList.remove('border-emerald-400', 'bg-emerald-50');
			this.classList.add('border-red-400', 'bg-red-50');
		} else {
			this.classList.remove('border-red-400', 'bg-red-50');
			this.classList.add('border-emerald-400', 'bg-emerald-50');
		}

		LoadDemandeConges();
	};

</script>