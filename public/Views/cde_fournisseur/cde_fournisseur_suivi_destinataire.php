<?php 

use Components\ActionComponents\ActionComponent;
use Components\ButtonComponents\ButtonComponent;

use Components\CardComponents\ErrorCardComponent;
use Components\EntityComponents\EntityComponents;
use Components\EntityComponents\CDEFourHComponents;

use Controllers\Actions\ActionsConversation;

use Controllers\ControllerConversation;

use Models\Entities\CDEFourH;
use Models\Entities\ConversationThread;
use Models\EntityLists\ListEtatModel;
use Models\EntityLists\ListUser;
use Components\EntityComponents\CDEFourLComponents;
use Utils\Session\DataHelper;

if (!isset($CDEFourH) || !($CDEFourH instanceof CDEFourH)) {
	$errors['view_CDEFourH'] = 'La "commande stock" est indéfinie ou invalide';
	$CDEFourH = new CDEFourH;
}

if (!isset($States) || !($States instanceof ListEtatModel)) {
	$errors['view_States'] = 'La liste des états possibles est indéfinie ou invalide';
	$States = new ListEtatModel;
}

if (!isset($ProductStates) || !($ProductStates instanceof ListEtatModel)) {
	$errors['view_ProductStates'] = 'La liste d\'états possibles sur les produits est indéfinie ou invalide';
	$States = new ListEtatModel;
}

if (!isset($Destinataires) || !($Destinataires instanceof ListUser)) {
	$errors['view_Destinataires'] = 'La liste de destinataires possibles est indéfinie ou invalide';
	$Destinataires = new ListUser;
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
				'exit_thread',
				ButtonComponent::RED_CLASS."",
				'Retour',
				DataHelper::Get('return_route')
			); ?>
		</div>

		<div class="w-3/5 flex justify-center">
			<span class="flex flex-col items-center ">
				<h2 id="thread_title" class="text-4xl text-center"><?= $CDEFourH ?>"</h2>
				<span class="text-2xl text-center">
					<?= 
						$CDEFourH->CreatedAt != null
						? $CDEFourH->CreatedAt->format('d/m/y')
						: 'indéfini'
					?>
				</span>
			</span>
		</div>

		<div class="w-1/5 flex justify-center">
			<?php if ($CDEFourH->ConversationThread === null): ?>
				<?php ActionComponent::Display(
					'chat',
					'index.php?controller='.ControllerConversation::class.'&action='.ActionsConversation::INIT.'&subject_type='.ConversationThread::SUBJECT_TYPE_CDE_FOUR.'&subject_id='.$CDEFourH->id,
					'public/Icons/chat.svg',
					'Discution',
					'blue',
					false,
					
					10, 
					10
				); ?>
			<?php else: ?>
				<?php ActionComponent::Display(
					'chat',
					'index.php?controller='.ControllerConversation::class.'&action='.ActionsConversation::THREAD.'&id='.$CDEFourH->id_conversation_thread.'&subject_type='.ConversationThread::SUBJECT_TYPE_CDE_FOUR.'&subject_id='.$CDEFourH->id,
					'public/Icons/chat.svg',
					'Discution',
					'blue',
					false,

					10, 
					10
				); ?>
			</button>
			<?php endif; ?>
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

		<section class="bg-white shadow sm:rounded-lg group">
			<div class="bg-gray-300 px-4 py-5 sm:px-6 grid grid-cols-2 items-center">
				<h3 class="leading-6 font-medium text-gray-900
					text-4xl 
					lg:text-lg 
				">
					Details de la commande
				</h3>
			</div>

			<!-- Infos -->
			<?php CDEFourHComponents::DisplayDescriptionListDestinataire($CDEFourH); ?>
		</section>

		<section class="flex flex-col self-center justify-center w-4/5 gap-y-4">
			<?php EntityComponents::DisplaySelect(
				$Destinataires,
				'id_destinataire',
				'id_destinataire',
				'Destinataire',
				true,
				true,
				$CDEFourH->id_destinataire,
			) ?>

			<?php EntityComponents::DisplaySelect(
				$States,
				'id_state',
				'id_state',
				'Etat de la demande',
				true,
				true,
				$CDEFourH->id_etat,
			) ?>
		</section>

		<section class="flex flex-col items-center 
			bg-gray-100 border rounded-lg px-4 py-6 shadow
			max-w-6xl 
			lg:max-w-4xl
		">
			<?php if ($CDEFourH->ListCDEFourL->IsEmpty()): ?>
				<h4 class="text-2xl">Aucun Produit</h4>
			<?php else: ?>
				<?php CDEFourLComponents::DisplayTableSuivisDestinataire($CDEFourH->ListCDEFourL, $ProductStates); ?>
			<?php endif; ?>
		</section>
	</main>
</body>

<script>
	//*
	document.querySelectorAll('[id^="state_"]').forEach(element => {
		element.onchange = UpdateProductState;
		element.disabled = false;
	});

	async function UpdateProductState() {
		this.disabled = true;
		const PRODUCT_ID = this.id.substring(6);
		const PRODUCT_STATE_ID = this.value;

		const URL = `src/ServicesAsync/CDEFourAync/AsyncCDEFourLUpdate.php?id=${PRODUCT_ID}&id_state=${PRODUCT_STATE_ID}`;

		const HTML = await GetHTML(URL);

		if (HTML != 'true') {
			console.error(HTML);
			document.getElementById('errors').innerHTML += HTML;
		}

		this.disabled = false;
	}


	//* Set a confirmation on the destinataire change select
	const DESTINATAIRE = document.getElementById('id_destinataire');
	DESTINATAIRE.onchange = UpdateDestinataire;
	DESTINATAIRE.disabled = false;
	
	async function UpdateDestinataire (e) {
		//* ask confirmation
		const option = this.options[this.selectedIndex].text;
		if(!confirm(`Confirmer le changement de destinataire de la demande stock en ${option}`)) {
			e.preventDefault();
			return;
		}

		//* disable modification until the service call ended
		const PRODUCT_STATES = document.querySelectorAll('[id^="state_"]');
		const STATE = document.getElementById('id_state');

		this.disabled = true;
		STATE.disabled = true;
		PRODUCT_STATES.forEach(element => {
			element.disabled = true;
		});

		//* Call the service
		const ID_DESTINATAIRE = this.value;

		const URL = `src/ServicesAsync/CDEFourAync/AsyncCDEFourHUpdate.php?id=<?= $CDEFourH->id ?>&id_destinataire=${ID_DESTINATAIRE}`;

		const HTML = await GetHTML(URL);

		//* handle error and re-enable modifications
		if (HTML != 'true') {
			console.error(HTML);
			document.getElementById('errors').innerHTML += HTML;
			
			this.disabled = false;
			STATE.disabled = false;
			PRODUCT_STATES.forEach(element => {
				element.disabled = false;
			});
			return;
		}

		//* reload the page because the user isnt the "destinataire" anymore
		location.reload();
	}


	//* Set a confirmation on the state change select
	const STATE = document.getElementById('id_state')
	STATE.onchange = UpdateState;
	STATE.disabled = false;
	
	async function UpdateState(e) {
		//* ask confirmation
		const option = this.options[this.selectedIndex].text;
		if(!confirm(`Confirmer le changement d\'état de la demande stock en "${option}"`)) {
			e.preventDefault();
			return;
		}

		//* disable modification until the service call ended
		const PRODUCT_STATES = document.querySelectorAll('[id^="state_"]');
		const DESTINATAIRE = document.getElementById('id_destinataire');

		this.disabled = true;
		DESTINATAIRE.disabled = true;
		PRODUCT_STATES.forEach(element => {
			element.disabled = true;
		});

		//* Call the service
		const ID_STATE = this.value;

		const URL = `src/ServicesAsync/CDEFourAync/AsyncCDEFourHUpdate.php?id=<?= $CDEFourH->id ?>&id_state=${ID_STATE}`;

		const HTML = await GetHTML(URL);

		//* handle error and re-enable modifications
		if (HTML != 'true') {
			console.error(HTML);
			document.getElementById('errors').innerHTML += HTML;

			e.preventDefault();
			this.disabled = false;
			DESTINATAIRE.disabled = false;
			PRODUCT_STATES.forEach(element => {
				element.disabled = false;
			});
			return;
		}
		
		//* if the demand is set to "Cloturé" (id N°4), reload the page to kick the user
		if (ID_STATE == 4) { 
			location.reload();
		}

		//* re-enable modifications
		this.disabled = false;
		DESTINATAIRE.disabled = false;
		PRODUCT_STATES.forEach(element => {
			element.disabled = false;
		});
	}

	
	//* Get the html from a given url
	async function GetHTML(url) {
		const response = await fetch(url, {
			method: "GET",
		});

		if (!response.ok) {
			console.error(`Response status: ${response.status}`);
			return 'error';
		}

		return response.text();
	}
</script>