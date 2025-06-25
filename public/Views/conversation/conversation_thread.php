<?php

use Components\ActionComponents\ActionComponent;
use Components\CardComponents\ErrorCardComponent;

use Models\Entities\CDEClientH;
use Models\Entities\CDEFourH;
use Models\Entities\DemandeDiverse;

use Core\Session\DataHelper;
use Core\Session\UserHelper;

use Controllers\ControllerConversation;
use Controllers\Actions\ActionsConversation;

use Controllers\ControllerSuiviDemandes;
use Controllers\Actions\ActionsSuiviDemandes;

use Models\Entities\ConversationThread;
use Models\Entities\ConversationMessage;

use Components\FormComponents\CheckboxComponent;
use Components\FormComponents\TextareaComponent;
use Components\ButtonComponents\ButtonComponent;
use Components\ButtonComponents\SubmitButtonComponent;


if (!($CurrentMessage instanceof ConversationMessage)) {
	throw new Exception("\$CurrentMessage must be an instance of 'ConversationMessage'");
}

if (!($Thread instanceof ConversationThread)) {
	throw new Exception("\$Thread must be an instance of 'ConversationThread'");
}

?>

<?php require "public/Views/head.php"; ?>

<body class="
	mx-auto
	bg-orange-300 
	max-w-6xl 
	lg:max-w-4xl 
">
	<header class="sticky 
		mb-6 top-0 right-0 left-0 px-8 py-6 gap-x-8 
		flex items-center
		bg-gray-100 border-b-4 border-orange-200 shadow 
		w-full 
		h-auto
	">
		<div class="w-1/5">
			<?php ButtonComponent::Display(
				'exit_thread',
				ButtonComponent::RED_CLASS."",
				'Retour',
				'index.php?controller='.ControllerConversation::class.'&action='.ActionsConversation::EXIT
			); ?>
		</div>

		<!-- flex flex-col items-center mx-auto mb-8 mt-8 static -->
		<div class="w-3/5">
			<span class="flex flex-col items-center ">
				<h2 id="thread_title" class="text-4xl text-center"><?= $Thread->title ?></h2>
				<span class="text-2xl text-center">
					<?= 
						$Thread->GetDate() != null
						? $Thread->GetDate()->format('d/m/y')
						: 'indéfini'
					?>
				</span>
			</span>
		</div>

		<div class="w-1/5">
			<?php if ($Thread->ConversationSubject instanceof CDEClientH): ?>
				<?php ActionComponent::Display(
					'view',
					'index.php?controller='.ControllerSuiviDemandes::class.'&action='.ActionsSuiviDemandes::DETAILS_CDE_CLIENT.'&id='.$Thread->ConversationSubject->GetId(),
					'public/Icons/view.svg',
					'Details',
					'blue',
					false,
					
					10, 
					10
				); ?>
			<?php elseif ($Thread->ConversationSubject instanceof CDEFourH): ?>
				<?php ActionComponent::Display(
					'view',
					'index.php?controller='.ControllerSuiviDemandes::class.'&action='.ActionsSuiviDemandes::DETAILS_CDE_FOURNISSEUR.'&id='.$Thread->ConversationSubject->GetId(),
					'public/Icons/view.svg',
					'Details',
					'blue',
					false,
					
					10, 
					10
				); ?>
			<?php elseif ($Thread->ConversationSubject instanceof DemandeDiverse): ?>
				<?php ActionComponent::Display(
					'view',
					'index.php?controller='.ControllerSuiviDemandes::class.'&action='.ActionsSuiviDemandes::DETAILS_DEMANDES_DIVERSES.'&id='.$Thread->ConversationSubject->GetId(),
					'public/Icons/view.svg',
					'Details',
					'blue',
					false,
					
					10, 
					10
				); ?>
			<?php endif; ?>
		</div>
	</header>
	
	<!-- <div class="sticky top-40 right-20">
		<?php ButtonComponent::Display(
			'show_participants',
			ButtonComponent::BLUE_CLASS."",
			'Participants',
		); ?>
	</div> -->

	<!-- Participants Aside Menu -->
	<div id="participants-menu" class="fixed top-0 right-0 h-full w-80 bg-white shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out z-50 overflow-y-auto">
		<div class="p-4 border-b border-gray-200">
			<div class="flex justify-between items-center">
				<h3 class="text-xl font-semibold">Participants</h3>
				<button onclick="toggleParticipantsMenu()" class="text-gray-500 hover:text-gray-700">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
					</svg>
				</button>
			</div>
		</div>
		<div id="participants-list" class="p-4">
			<!-- Participants will be loaded here -->
			<div class="text-center text-gray-500">
				Chargement des participants...
			</div>
		</div>
	</div>

	<!-- Backdrop for the aside menu -->
	<div id="menu-backdrop" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden" onclick="toggleParticipantsMenu()"></div>

	<main class="
		flex flex-col px-8 py-6 gap-y-8 
		bg-orange-200 rounded-lg 
		border-1 border-orange-400 
		shadow-sm shadow-orange-500 

		lg:bg-orange-200 lg:rounded-lg 
		lg:border-1 lg:border-orange-400 
		lg:shadow-sm lg:shadow-orange-500 
	">
		<section class="flex flex-col px-10">
			<div id="thread" class="flex flex-col">
				<!-- The Conversation Thread will be here -->
			</div>

			<div class="flex justify-end ">
				<?php ErrorCardComponent::Display(); ?>
				<form method="post" action="index.php?controller=<?= ControllerConversation::class ?>&action=<?= ActionsConversation::NEW_MESSAGE_PROCESS ?>"
					class="flex flex-col gap-y-2 
					bg-gray-100 border rounded-lg px-8 py-6 my-8 shadow
					max-w-6xl 
					lg:max-w-4xl
					"
				>
					<div class="">
						<label for="name" class="block text-gray-700 font-medium mb-2
							te
							xt-4xl 
							lg:text-base "
						>Message</label>

						<?php TextareaComponent::Display(
							"new_message_content",
							TextareaComponent::DEFAULT_CLASS,
							"message",
							true,
							$CurrentMessage->content ?? ""
						); ?>
					</div>

					<!-- <?php CheckboxComponent::Display(
						"new_message_is_important",
						TextareaComponent::DEFAULT_CLASS,
						"is_important",
						false,
						$CurrentMessage->is_important ?? "",
						"yes",
						'Est important'
					); ?> -->

					<?php SubmitButtonComponent::Display(
						'new_message_sub',
						ButtonComponent::BLUE_CLASS."mx-8",
						'Envoyer',
					); ?>
				</form>
			</div>
		</section>
	</main>

</body>


<script>
	// delay function
	const delay = ms => new Promise(res => setTimeout(res, ms));
	
	
	// load the conversation thread
	document.addEventListener("DOMContentLoaded", function() {
		LoadThread();
		// LoadParticipants();
	});


	// document.getElementById('show_participants').onclick = LoadParticipants;


	//* Load and update the thread
	async function LoadThread() {
		const DELAY_AMOUNT = 30_000
		const API_URL = `src/ServicesAsync/ConversationAsync/AsyncConversation.php?thread_id=<?= $Thread->id ?>&user_id=<?= UserHelper::GetUserId() ?>`;
		const DIV = document.getElementById('thread');
		
		if (DIV == null) {
			throw new Error(`Can't find the thread div`);
		}

		while(true) {
			let html = await GetHTML(API_URL);

			DIV.innerHTML = html;

			await delay(DELAY_AMOUNT);
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

	//* Toggle the participants menu
	function toggleParticipantsMenu() {
		const menu = document.getElementById('participants-menu');
		const backdrop = document.getElementById('menu-backdrop');
		
		if (menu.classList.contains('translate-x-full')) {
			// Show menu
			menu.classList.remove('translate-x-full');
			backdrop.classList.remove('hidden');
			document.body.classList.add('overflow-hidden');
		} else {
			// Hide menu
			menu.classList.add('translate-x-full');
			backdrop.classList.add('hidden');
			document.body.classList.remove('overflow-hidden');
		}
	}

	//* Load participants for the conversation
	// async function LoadParticipants() {
	// 	const PARTICIPANTS_URL = `src/ServicesAsync/ConversationAsync/AsyncParticipants.php?thread_id=<?= $Thread->id ?>`;
	// 	const participantsList = document.getElementById('participants-list');
		
	// 	try {
	// 		const html = await GetHTML(PARTICIPANTS_URL);
	// 		participantsList.innerHTML = html;
	// 	} catch (error) {
	// 		participantsList.innerHTML = `
	// 			<div class="text-center text-red-500">
	// 				Erreur lors du chargement des participants: ${error.message}
	// 			</div>
	// 		`;
	// 	}
	// }

</script>