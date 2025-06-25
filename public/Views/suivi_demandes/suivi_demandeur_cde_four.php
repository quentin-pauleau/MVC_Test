<?php 

use Components\ActionComponents\ActionComponent;
use Components\ButtonComponents\ButtonComponent;

use Components\CardComponents\ErrorCardComponent;
use Components\EntityComponents\CDEFourHComponents;

use Controllers\Actions\ActionsConversation;

use Controllers\ControllerConversation;

use Models\Entities\CDEFourH;
use Models\Entities\ConversationThread;
use Components\EntityComponents\CDEFourLComponents;
use Core\Session\DataHelper;

if (!isset($CDEFourH) || !($CDEFourH instanceof CDEFourH)) {
	$errors['view_CDEFourH'] = 'La "commande stock" est indéfinie ou invalide';
	$CDEFourH = new CDEFourH;
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
				<h2 id="thread_title" class="text-4xl text-center">Demande fournisseur "<?= $CDEFourH->Fournisseur ?>"</h2>
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
			<?php CDEFourHComponents::DisplayDescriptionListDemandeur($CDEFourH); ?>
		</section>

		
		<section class="flex flex-col items-center 
			bg-gray-100 border rounded-lg px-4 py-6 shadow
			max-w-6xl 
			lg:max-w-4xl
		">
			<?php if ($CDEFourH->ListCDEFourL->IsEmpty()): ?>
				<h4 class="text-2xl">Aucun Produit</h4>
			<?php else: ?>
				<?php CDEFourLComponents::DisplayTableSuivisDemandeur($CDEFourH->ListCDEFourL) ?>
			<?php endif; ?>
		</section>
	</main>
</body>