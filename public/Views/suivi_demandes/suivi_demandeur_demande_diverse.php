<?php 

use Components\ActionComponents\ActionComponent;
use Components\ButtonComponents\ButtonComponent;
use Components\CardComponents\ErrorCardComponent;
use Components\EntityComponents\DemandeDiverseComponents;
use Controllers\Actions\ActionsConversation;
use Controllers\ControllerConversation;
use Models\Entities\ConversationThread;
use Models\Entities\DemandeDiverse;
use Core\Session\DataHelper;

if (!isset($DemandeDiverse) || !($DemandeDiverse instanceof DemandeDiverse)) {
	$errors['view_DemandeDiverse'] = 'La demande diverse est indéfinie ou invalide';
	$CDEClientH = new DemandeDiverse;
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
				<h2 id="thread_title" class="text-4xl text-center">Demande diverse de <?= $DemandeDiverse->Demandeur ?></h2>
				<span class="text-2xl text-center">
					<?= 
						$DemandeDiverse->CreatedAt != null
						? $DemandeDiverse->CreatedAt->format('d/m/y')
						: 'indéfini'
					?>
				</span>
			</span>
		</div>

		<div class="w-1/5 flex justify-center">
			<?php if ($DemandeDiverse->ConversationThread === null): ?>
				<?php ActionComponent::Display(
					'chat',
					'index.php?controller='.ControllerConversation::class.'&action='.ActionsConversation::INIT.'&subject_type='.ConversationThread::SUBJECT_TYPE_DEMANDE_DIVERSE.'&subject_id='.$DemandeDiverse->id,
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
					'index.php?controller='.ControllerConversation::class.'&action='.ActionsConversation::THREAD.'&id='.$DemandeDiverse->id_conversation_thread.'&subject_type='.ConversationThread::SUBJECT_TYPE_DEMANDE_DIVERSE.'&subject_id='.$DemandeDiverse->id,
					'public/Icons/chat.svg',
					'Discution',
					'blue',
					false,

					10, 
					10
				); ?>
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
					Details de la demande
				</h3>
			</div>

			<!-- Infos -->
			<?php DemandeDiverseComponents::DisplayDescriptionListDemandeur($DemandeDiverse); ?>
		</section>

		
		<section class="flex flex-col 
			bg-gray-100 border rounded-lg px-10 py-6 shadow
			max-w-6xl 
			lg:max-w-4xl
			gap-y-4
		">
			<h2 class="text-2xl lg:text-3xl font-bold text-center">Contenu de la demande</h2>
			<p class="text-xl lg:text-xl text-left">
				<?= $DemandeDiverse->demande === '' ? 'Non renseigné' : $DemandeDiverse->demande ?>
			</p>
		</section>
	</main>
</body>