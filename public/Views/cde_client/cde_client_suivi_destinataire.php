<?php 

use Utils\Session\DataHelper;
use Models\Entities\CDEClientH;
use Models\EntityLists\ListUser;
use Models\EntityLists\ListEtatModel;
use Controllers\ControllerConversation;
use Models\Entities\ConversationThread;
use Controllers\Actions\ActionsConversation;
use Components\ActionComponents\ActionComponent;
use Components\ButtonComponents\ButtonComponent;
use Components\CardComponents\ErrorCardComponent;
use Components\EntityComponents\EntityComponents;
use Components\EntityComponents\CDEClientHComponents;
use Components\EntityComponents\CDEClientLComponents;

if (!isset($CDEClientH) || !($CDEClientH instanceof CDEClientH)) {
	$errors['view_CDEClient'] = 'La "demande client" est indéfinie ou invalide';
	$CDEClientH = new CDEClientH;
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
				<h2 id="thread_title" class="text-4xl text-center"><?= $CDEClientH->devis == 'devis' ? 'Devis' : 'Commande' ?> client "<?= $CDEClientH->nom_client ?>"</h2>
				<span class="text-2xl text-center">
					<?= 
						$CDEClientH->CreatedAt != null
						? $CDEClientH->CreatedAt->format('d/m/y')
						: 'indéfini'
					?>
				</span>
			</span>
		</div>

		<div class="w-1/5 flex justify-center">
			<?php if ($CDEClientH->ConversationThread === null): ?>
				<?php ActionComponent::Display(
					'chat',
					'index.php?controller='.ControllerConversation::class.'&action='.ActionsConversation::INIT.'&subject_type='.ConversationThread::SUBJECT_TYPE_CDE_CLIENT.'&subject_id='.$CDEClientH->id,
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
					'index.php?controller='.ControllerConversation::class.'&action='.ActionsConversation::THREAD.'&id='.$CDEClientH->id_conversation_thread.'&subject_type='.ConversationThread::SUBJECT_TYPE_CDE_CLIENT.'&subject_id='.$CDEClientH->id,
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
		<section id="error-section">
			<?php ErrorCardComponent::Display('error-card', $errors) ?>
		</section>


		<section class="bg-white shadow sm:rounded-lg group">
			<div class="bg-gray-300 px-4 py-5 sm:px-6 grid grid-cols-2 items-center">
				<h3 class="leading-6 font-medium text-gray-900
					text-4xl 
					lg:text-lg 
				">
					Details <?= $CDEClientH->GetTypeSentence() ?>
				</h3>
			</div>

			<!-- Infos -->
			<?php CDEClientHComponents::DisplayDescriptionListDestinataire($CDEClientH); ?>
		</section>


		<section class="flex flex-col self-center justify-center w-4/5 gap-y-4">
			<?php EntityComponents::DisplaySelect(
				$Destinataires,
				'id_destinataire',
				'id_destinataire',
				'Destinataire',
				true,
				true,
				$CDEClientH->id_destinataire,
			) ?>

			<?php EntityComponents::DisplaySelect(
				$States,
				'id_state',
				'id_state',
				'Etat de la demande',
				true,
				true,
				$CDEClientH->id_etat,
			) ?>
		</section>
		
		<section class="flex flex-col items-center 
			bg-gray-100 border rounded-lg px-4 py-6 shadow
			max-w-6xl 
			lg:max-w-4xl
		">
			<?php if ($CDEClientH->Products->IsEmpty()): ?>
				<h4 class="text-2xl">Aucun Produit</h4>
			<?php else: ?>
				<?php CDEClientLComponents::DisplayTableSuivisDestinataire($CDEClientH->Products, $ProductStates); ?>
			<?php endif; ?>
		</section>
	</main>
</body>

<script>
	//* call to async service on a product state change
	document.querySelectorAll('[id^="state_"]').forEach(element => {
		element.onchange = UpdateProductState;
		element.disabled = false;
	});

	async function UpdateProductState() {
		//* disable the select of the product state to update
		this.disabled = true;

		//* async service call
		const PRODUCT_ID = this.id.substring(6);
		const PRODUCT_STATE_ID = this.value;

		const URL = `src/ServicesAsync/CDEClientAsync/AsyncCDEClientLUpdate.php?id=${PRODUCT_ID}&id_state=${PRODUCT_STATE_ID}`;

		const HTML = await GetHTML(URL);

		//* error handleing
		if (HTML != 'true') {
			console.error(HTML);
			document.getElementById('errors').innerHTML += HTML;
		}

		//* re-enable the select of the product state
		this.disabled = false;
	}


	//* Set a confirmation on the destinataire change select
	const DESTINATAIRE = document.getElementById('id_destinataire');
	DESTINATAIRE.onchange = UpdateDestinataire;
	DESTINATAIRE.disabled = false;
	
	async function UpdateDestinataire (e) {
		//* ask confirmation
		const option = this.options[this.selectedIndex].text;
		if(!confirm(`Confirmer le changement de destinataire <?= $CDEClientH->GetTypeSentence() ?> en ${option}`)) {
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

		const URL = `src/ServicesAsync/CDEClientAsync/AsyncCDEClientHUpdate.php?id=<?= $CDEClientH->id ?>&id_destinataire=${ID_DESTINATAIRE}`;

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
	const STATE = document.getElementById('id_state');
	STATE.onchange = UpdateState;
	STATE.disabled = false;
	

	async function UpdateState(e) {
		//* ask confirmation
		const option = this.options[this.selectedIndex].text;
		if(!confirm(`Confirmer le changement d\'état <?= $CDEClientH->GetTypeSentence() ?> en "${option}"`)) {
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

		const URL = `src/ServicesAsync/CDEClientAsync/AsyncCDEClientHUpdate.php?id=<?= $CDEClientH->id ?>&id_state=${ID_STATE}`;

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