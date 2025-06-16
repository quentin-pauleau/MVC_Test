<?php
use Components\EntityComponents\EntityComponents;
use Components\FormComponents\InputComponents\InputTextComponent;
use Components\ButtonComponents\ButtonComponent;
use Components\CardComponents\ErrorCardComponent;

use Controllers\ControllerMenu;
use Controllers\Actions\ActionsMenu;

use Models\EntityLists\ListEtatModel;
use Models\EntityLists\ListFournisseur;
use Models\EntityLists\ListUser;
use Utils\Session\UserHelper;

// filtering values
if (!isset($ListClientState) || !($ListClientState instanceof ListEtatModel)) {
	throw new Exception('liste d\'etat possible sur les demandes clients invalide');
}

if (!isset($ListFourState) || !($ListFourState instanceof ListEtatModel)) {
	throw new Exception('liste d\'etat possible sur les demandes fournisseur invalide');
}

if (!isset($ListDemandeDiverseState) || !($ListDemandeDiverseState instanceof ListEtatModel)) {
	throw new Exception('liste d\'etat possible sur les demandes diverses invalide');
}

if (!isset($ListDemandeur) || !($ListDemandeur instanceof ListUser)) {
	throw new Exception('liste des demandeurs possible invalide');
}

if (!isset($ListDestinataire) || !($ListDestinataire instanceof ListUser)) {
	throw new Exception('liste des destinataires invalide');
}

if (!isset($ListFournisseur) || !($ListFournisseur instanceof ListFournisseur)) {
	throw new Exception('liste des fournisseur invalide');
}

if (!isset($defaultStateId)) {
	$defaultStateId = null;
}

$view = $is_demandeur ? 'demandeur' : ($is_destinataire ? 'destinataire' : 'details');


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
		<div class="w-1/5">
			<?php ButtonComponent::Display(
				'exit_thread',
				ButtonComponent::RED_CLASS."",
				'Retour',
				'index.php?controller='.ControllerMenu::class.'&action='.ActionsMenu::MAIN
			); ?>
		</div>

		<div class="w-3/5">
			<h2 id="thread_title" class="text-4xl text-center">
				<?php if ($is_demandeur): ?>
					Mes Demandes
				<?php elseif ($is_destinataire): ?>
					Demandes A R&eacute;aliser
				<?php else: ?>
					Toutes Les Demandes
				<?php endif; ?>
			</h2>
		</div>

		<div class="w-1/5">
			<!--
			
			-->
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
		<section id="error-section">
			<?php ErrorCardComponent::Display('error-card', $errors) ?>
		</section>


		<!-- Commandes client -->
		<section class="flex flex-col items-center 
			bg-gray-100 border rounded-lg px-4 py-6 shadow
			max-w-6xl 
			lg:max-w-4xl
		">
			<details class="group/list flex flex-col w-full group-open/list:gap-y-8">
				<summary class="flex flex-col items-center font-medium cursor-pointer list-none px-20">
					<!-- Header of the summary -->
					<span class="flex">
						<span class="text-4xl relative bottom-2">
							Commandes Clients
						</span>
						<span class="transition group-open/list:rotate-180 group-open/list:bottom-2 group-open/list:relative mx-2">
							<svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24">
								<path d="M6 9l6 6 6-6"></path>
							</svg>
						</span>
					</span>

					<!-- Content of the summary -->
					<span id="commande_client_count" class="text-2xl">
						<!-- Async Call -->
					</span>
				</summary>
				
				<!-- Filters -->
				<div class="flex flex-col items-center gap-y-4">
					<div class="flex flex-col items-center w-4/5">
						<label for="commande_client_nom" class="block text-gray-700 font-medium mb-2 text-center
							text-2xl 
							lg:text-base 
						">Nom Client</label>
						<?php InputTextComponent::Display(
							'commande_client_nom',
							InputTextComponent::DEFAULT_CLASS,
							'commande_client_nom',
						); ?>
					</div>

					<div class="w-4/5">
						<?php if ($is_demandeur): ?>
							<input class="hidden" id="commande_client_demandeur" value="<?= UserHelper::GetUserId() ?>" hidden/>
						<?php else: ?>
							<?php EntityComponents::DisplaySelect(
								$ListDemandeur,
								'commande_client_demandeur',
								'commande_client_demandeur',
								'Demandeur',
								// false,
								// false,
								// $defaultDemandeurId
							); ?>
						<?php endif; ?>
					</div>

					<div class="w-4/5">
						<?php if ($is_destinataire): ?>
							<input class="hidden" id="commande_client_destinataire" value="<?= UserHelper::GetUserId() ?>"/>
						<?php else: ?>
							<?php EntityComponents::DisplaySelect(
								$ListDestinataire,
								'commande_client_destinataire',
								'commande_client_destinataire',
								'Destinataire',
								// false,
								// false,
								// $defaultDestinataireId
							); ?>
						<?php endif; ?>
					</div>
					
					<div>
						<h4 class="text-2xl lg:text-base text-center block text-gray-700 font-medium ">Etats</h4>
						<?php EntityComponents::DisplayCheckboxSelection(
							$ListClientState,
							"commande_client_state_",
							"commande_client_state",
							true,
							$defaultStateId
						); ?>
					</div>
				</div>

				<!-- Table -->
				<div id="commande_client_table">
					<!-- Async Call -->
				</div>
			</details>
		</section>


		<!-- Commandes/devis client -->
		<section class="flex flex-col items-center 
			bg-gray-100 border rounded-lg px-4 py-6 shadow
			max-w-6xl 
			lg:max-w-4xl
		">
			<details class="group/list flex flex-col w-full group-open/list:gap-y-8">
				<summary class="flex flex-col items-center font-medium cursor-pointer list-none px-20">
					<!-- Header of the summary -->
					<span class="flex">
						<span class="text-4xl relative bottom-2">
							Devis Clients
						</span>
						<span class="transition group-open/list:rotate-180 group-open/list:bottom-2 group-open/list:relative mx-2">
							<svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24">
								<path d="M6 9l6 6 6-6"></path>
							</svg>
						</span>
					</span>

					<!-- Content of the summary -->
					<span id="devis_client_count" class="text-2xl">
						<!-- Async Call -->
					</span>
				</summary>

				
				<!-- Filters -->
				<div class="flex flex-col items-center gap-y-4">
					<div class="flex flex-col items-center w-4/5">
						<label for="devis_client_nom" class="block text-gray-700 font-medium mb-2 text-center
							text-2xl 
							lg:text-base 
						">Nom Client</label>
						<?php InputTextComponent::Display(
							'devis_client_nom',
							InputTextComponent::DEFAULT_CLASS,
							'devis_client_nom',
						); ?>
					</div>

					<div class="w-4/5">
						<?php if ($is_demandeur): ?>
							<input class="hidden" id="devis_client_demandeur" value="<?= UserHelper::GetUserId() ?>" hidden/>
						<?php else: ?>
							<?php EntityComponents::DisplaySelect(
								$ListDemandeur,
								'devis_client_demandeur',
								'devis_client_demandeur',
								'Demandeur',
								// false,
								// $defaultDemandeurId
							); ?>
						<?php endif; ?>
					</div>

					<div class="w-4/5">
						<?php if ($is_destinataire): ?>
							<input class="hidden" id="devis_client_destinataire" value="<?= UserHelper::GetUserId() ?>"/>
						<?php else: ?>
							<?php EntityComponents::DisplaySelect(
								$ListDestinataire,
								'devis_client_destinataire',
								'devis_client_destinataire',
								'Destinataire',
								// false,
								// false,
								// $defaultDestinataireId
							); ?>
						<?php endif; ?>
					</div>

					<div>
						<h4 class="text-2xl lg:text-base text-center block text-gray-700 font-medium ">Etats</h4>
						<?php EntityComponents::DisplayCheckboxSelection(
							$ListClientState,
							"devis_client_state_",
							"devis_client_state",
							true,
							$defaultStateId
						); ?>
					</div>
				</div>

				<!-- Table -->
				<div id="devis_client_table">
					<!-- Async Call -->
				</div>
			</details>
		</section>

		
		<!-- Commandes fournisseur -->
		<section class="flex flex-col items-center 
			bg-gray-100 border rounded-lg px-4 py-6 shadow
			max-w-6xl 
			lg:max-w-4xl
		">
			<details class="group/list flex flex-col w-full group-open/list:gap-y-8">
				<summary class="flex flex-col items-center font-medium cursor-pointer list-none px-20">
					<!-- Header of the summary -->
					<span class="flex">
						<span class="text-4xl relative bottom-2">
							Commandes Stock
						</span>

						<span class="transition group-open/list:rotate-180 group-open/list:bottom-2 group-open/list:relative mx-2">
							<svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24">
								<path d="M6 9l6 6 6-6"></path>
							</svg>
						</span>
					</span>

					<!-- Content of the summary -->
					<span id="fournisseur_count" class="text-2xl">
						<!-- Async Call -->
					</span>
				</summary>


				<!-- Filters -->
				<div class="flex flex-col items-center gap-y-4">
					<div class="w-4/5">
						<?php EntityComponents::DisplaySelect(
							$ListFournisseur,
							'fournisseur_fournisseur',
							'fournisseur_fournisseur',
							'Fournisseur',
							// false,
							// false,
							// $defaultFournisseurId
						); ?>
					</div>

					<div class="w-4/5">
						<?php if ($is_demandeur): ?>
							<input class="hidden" id="fournisseur_demandeur" value="<?= UserHelper::GetUserId() ?>" hidden/>
						<?php else: ?>
							<?php EntityComponents::DisplaySelect(
								$ListDemandeur,
								'fournisseur_demandeur',
								'fournisseur_demandeur',
								'Demandeur',
								// false,
								// false,
								// $defaultDemandeurId
							); ?>
						<?php endif; ?>
					</div>

					<div class="w-4/5">
						<?php if ($is_destinataire): ?>
							<input class="hidden" id="fournisseur_destinataire" value="<?= UserHelper::GetUserId() ?>"/>
						<?php else: ?>
							<?php EntityComponents::DisplaySelect(
								$ListDestinataire,
								'fournisseur_destinataire',
								'fournisseur_destinataire',
								'Destinataire',
								// false,
								// false,
								// $defaultDestinataireId
							); ?>
						<?php endif; ?>
					</div>

					<div>
						<h4 class="text-2xl lg:text-base text-center block text-gray-700 font-medium ">Etats</h4>
						<?php EntityComponents::DisplayCheckboxSelection(
							$ListFourState,
							"fournisseur_state_",
							"fournisseur_state",
							true,
							$defaultStateId
						); ?>
					</div>
				</div>

				<!-- Table -->
				<div id="fournisseur_table">
					<!-- Async Call -->
				</div>
			</details>
		</section>
		
		
		<!-- Demandes diverses -->
		<section class=" flex flex-col items-center 
			bg-gray-100 border rounded-lg px-8 py-6 shadow
			max-w-6xl 
			lg:max-w-4xl
		">
			<details class="group/list flex flex-col w-full group-open/list:gap-y-8">
				<summary class="flex flex-col items-center font-medium cursor-pointer list-none px-20">
					<!-- Header of the summary -->
					<span class="flex">
						<span class="text-4xl relative bottom-2">
							Demandes Diverses
						</span>
						
						<span class="transition group-open/list:rotate-180 group-open/list:bottom-2 group-open/list:relative mx-2">
							<svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24">
								<path d="M6 9l6 6 6-6"></path>
							</svg>
						</span>
					</span>

					<!-- Content of the summary -->
					<span id="demandes_diverses_count" class="text-2xl">
						<!-- Async Call -->
					</span>
				</summary>


				<!-- Filters -->
				<div class="flex flex-col items-center gap-y-4">
					<div class="flex flex-col items-center w-4/5">
						<label for="demandes_diverses_content" class="block text-gray-700 font-medium mb-2 text-center
							text-2xl 
							lg:text-base 
						">Demande contient</label>
						<?php InputTextComponent::Display(
							'demandes_diverses_content',
							InputTextComponent::DEFAULT_CLASS,
							'demandes_diverses_content',
						); ?>
					</div>

					<div class="w-4/5">
						<?php if ($is_demandeur): ?>
							<input class="hidden" id="demandes_diverses_demandeur" value="<?= UserHelper::GetUserId() ?>" hidden/>
						<?php else: ?>
							<?php EntityComponents::DisplaySelect(
								$ListDemandeur,
								'demandes_diverses_demandeur',
								'demandes_diverses_demandeur',
								'Demandeur',
								// false,
								// false
								// $defaultDemandeurId
							); ?>
						<?php endif; ?>
					</div>

					<div class="w-4/5">
						<?php if ($is_destinataire): ?>
							<input class="hidden" id="demandes_diverses_destinataire" value="<?= UserHelper::GetUserId() ?>"/>
						<?php else: ?>
							<?php EntityComponents::DisplaySelect(
								$ListDestinataire,
								'demandes_diverses_destinataire',
								'demandes_diverses_destinataire',
								'Destinataire',
								// false,
								// false,
								// $defaultDestinataireId
							); ?>
						<?php endif; ?>
					</div>

					<div>
						<h4 class="text-2xl lg:text-base text-center block text-gray-700 font-medium ">Etats</h4>
						<?php EntityComponents::DisplayCheckboxSelection(
							$ListDemandeDiverseState,
							"demandes_diverses_state_",
							"demandes_diverses_state",
							true,
							$defaultStateId
						); ?>
					</div>
				</div>

				<!-- Table -->
				<div id="demandes_diverses_table">
					<!-- Async Call -->
				</div>
			</details>
		</section>
	</main>
</body>

<script>
	//* load tables with default values and set the onchange events
	LoadCommandeClient();
	document.getElementById('commande_client_nom').onchange = LoadCommandeClient;
	document.querySelectorAll(`input[name="commande_client_state"]`).forEach(element => {
		element.onchange = LoadCommandeClient;
	});
	document.getElementById('commande_client_demandeur').onchange = LoadCommandeClient;
	document.getElementById('commande_client_destinataire').onchange = LoadCommandeClient;


	LoadDevisClient();
	document.getElementById('devis_client_nom').onchange = LoadDevisClient;
	document.querySelectorAll(`input[name="devis_client_state"]`).forEach(element => {
		element.onchange = LoadDevisClient;
	});
	document.getElementById('devis_client_demandeur').onchange = LoadDevisClient;
	document.getElementById('devis_client_destinataire').onchange = LoadDevisClient;


	LoadFournisseur();
	document.getElementById('fournisseur_fournisseur').onchange = LoadFournisseur;
	document.querySelectorAll('input[name="fournisseur_state"]').forEach(element => {
		element.onchange = LoadFournisseur;
	});
	document.getElementById('fournisseur_demandeur').onchange = LoadFournisseur;
	document.getElementById('fournisseur_destinataire').onchange = LoadFournisseur;


	LoadDemandesDiverses();
	document.getElementById('demandes_diverses_content').onchange = LoadDemandesDiverses;
	document.querySelectorAll(`input[name="demandes_diverses_state"]`).forEach(element => {
		element.onchange = LoadDemandesDiverses;
	});
	document.getElementById('demandes_diverses_demandeur').onchange = LoadDemandesDiverses;
	document.getElementById('demandes_diverses_destinataire').onchange = LoadDemandesDiverses;


	//* Load and update the tables functions
	async function LoadCommandeClient() {
		const VIEW = "<?= $view ?>";
		const ID_USER = "<?= UserHelper::GetUserId(); ?>";
		const TYPE = "commande";
		const NOM_CLIENT = document.getElementById('commande_client_nom').value;
		const ID_STATE = GetCheckedValues('commande_client_state').join('|');
		const ID_DEMANDEUR = document.getElementById('commande_client_demandeur').value;
		const ID_DESTINATAIRE = document.getElementById('commande_client_destinataire').value;

		const TABLE_API_URL = `src/ServicesAsync/CDEClientAsync/AsyncCDEClientHTable.php?view=${VIEW}&id_user=${ID_USER}&type=${TYPE}&nom_client=${NOM_CLIENT}&id_state=${ID_STATE}&id_demandeur=${ID_DEMANDEUR}&id_destinataire=${ID_DESTINATAIRE}`;
		const COUNT_API_URL = `src/ServicesAsync/CDEClientAsync/AsyncCDEClientHTable.php?view=count&type=${TYPE}&nom_client=${NOM_CLIENT}&id_state=${ID_STATE}&id_demandeur=${ID_DEMANDEUR}&id_destinataire=${ID_DESTINATAIRE}`;
		
		const TABLE_DIV = document.getElementById('commande_client_table');
		const COUNT_DIV = document.getElementById('commande_client_count');

		if (TABLE_DIV == null || COUNT_DIV == null) {
			throw new Error('Can\'t find the commande_client_table div or commande_client_count div');
		}

		const TABLE = await GetHTML(TABLE_API_URL);
		const COUNT = await GetHTML(COUNT_API_URL);

		TABLE_DIV.innerHTML = TABLE;
		COUNT_DIV.innerHTML = `${COUNT} Commande Client`;
	}

	async function LoadDevisClient() {
		//* get all the filtering values
		const VIEW = "<?= $view ?>";
		const ID_USER = "<?= UserHelper::GetUserId() ?>";
		const TYPE = "devis";
		const NOM_CLIENT = document.getElementById('devis_client_nom').value;
		const ID_STATE = GetCheckedValues('devis_client_state').join('|');
		const ID_DEMANDEUR = document.getElementById('devis_client_demandeur').value;
		const ID_DESTINATAIRE = document.getElementById('devis_client_destinataire').value;
		

		//* call the service
		const TABLE_API_URL = `src/ServicesAsync/CDEClientAsync/AsyncCDEClientHTable.php?view=${VIEW}&id_user=${ID_USER}&type=${TYPE}&nom_client=${NOM_CLIENT}&id_state=${ID_STATE}&id_demandeur=${ID_DEMANDEUR}&id_destinataire=${ID_DESTINATAIRE}`;
		const TABLE_DIV = document.getElementById('devis_client_table');

		const COUNT_API_URL = `src/ServicesAsync/CDEClientAsync/AsyncCDEClientHTable.php?view=count&type=${TYPE}&nom_client=${NOM_CLIENT}&id_state=${ID_STATE}&id_demandeur=${ID_DEMANDEUR}&id_destinataire=${ID_DESTINATAIRE}`;
		const COUNT_DIV = document.getElementById('devis_client_count');
		
		if (TABLE_DIV == null || COUNT_DIV == null) {
			throw new Error('Can\'t find the devis_client_table div or devis_client_table div');
		}

		const TABLE = await GetHTML(TABLE_API_URL);
		const COUNT = await GetHTML(COUNT_API_URL);

		TABLE_DIV.innerHTML = TABLE;
		COUNT_DIV.innerHTML = `${COUNT} Devis Client`;
	}

	async function LoadFournisseur() {
		//* get all the filtering values
		const VIEW = "<?= $view ?>";
		const ID_USER = "<?= UserHelper::GetUserId() ?>";
		const ID_FOURNISSEUR = document.getElementById('fournisseur_fournisseur').value;
		const ID_STATE = GetCheckedValues('fournisseur_state').join('|');
		const ID_DEMANDEUR = document.getElementById('fournisseur_demandeur').value;
		const ID_DESTINATAIRE = document.getElementById('fournisseur_destinataire').value;

		//* call the service
		const TABLE_API_URL = `src/ServicesAsync/CDEFourAync/AsyncCDEFourHTable.php?view=${VIEW}&id_user=${ID_USER}&id_fournisseur=${ID_FOURNISSEUR}&id_state=${ID_STATE}&id_demandeur=${ID_DEMANDEUR}&id_destinataire=${ID_DESTINATAIRE}`;
		const TABLE_DIV = document.getElementById('fournisseur_table');

		const COUNT_API_URL = `src/ServicesAsync/CDEFourAync/AsyncCDEFourHTable.php?view=count&id_fournisseur=${ID_FOURNISSEUR}&id_state=${ID_STATE}&id_demandeur=${ID_DEMANDEUR}&id_destinataire=${ID_DESTINATAIRE}`;
		const COUNT_DIV = document.getElementById('fournisseur_count');
		
		if (TABLE_DIV == null || COUNT_DIV == null) {
			throw new Error(`Can't find the fournisseur_table div or fournisseur_count div`);
		}

		const TABLE = await GetHTML(TABLE_API_URL);
		const COUNT = await GetHTML(COUNT_API_URL);

		TABLE_DIV.innerHTML = TABLE;
		COUNT_DIV.innerHTML = `${COUNT} Commandes Stock`;
	}

	async function LoadDemandesDiverses() {
		//* get all the filtering values
		const VIEW = "<?= $view ?>";
		const ID_USER = "<?= UserHelper::GetUserId() ?>";
		const CONTENT = document.getElementById('demandes_diverses_content').value;
		const ID_STATES = GetCheckedValues('demandes_diverses_state').join('|');
		
		const ID_DEMANDEUR = document.getElementById('demandes_diverses_demandeur').value;
		const ID_DESTINATAIRE = document.getElementById('demandes_diverses_destinataire').value;

		//* call the service
		const TABLE_API_URL = `src/ServicesAsync/DemandeDiverseAsync/AsyncDemandesDiversesTable.php?view=${VIEW}&id_user=${ID_USER}&content=${CONTENT}&id_state=${ID_STATES}&id_demandeur=${ID_DEMANDEUR}&id_destinataire=${ID_DESTINATAIRE}`;
		const TABLE_DIV = document.getElementById('demandes_diverses_table');

		const COUNT_API_URL = `src/ServicesAsync/DemandeDiverseAsync/AsyncDemandesDiversesTable.php?view=count&content=${CONTENT}&id_state=${ID_STATES}&id_demandeur=${ID_DEMANDEUR}&id_destinataire=${ID_DESTINATAIRE}`;
		const COUNT_DIV = document.getElementById('demandes_diverses_count');
		
		if (TABLE_DIV == null || COUNT_DIV == null) {
			throw new Error(`Can't find the demandes_diverses_table div or demandes_diverses_count div`);
		}

		const TABLE = await GetHTML(TABLE_API_URL);
		const COUNT = await GetHTML(COUNT_API_URL);

		TABLE_DIV.innerHTML = TABLE;
		COUNT_DIV.innerHTML = `${COUNT} Demandes Diverses`;
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
</script>