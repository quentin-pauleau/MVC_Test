<?php 

use Components\ActionComponents\ActionComponent;
use Components\ButtonComponents\ButtonComponent;
use Components\ButtonComponents\SubmitButtonComponent;
use Components\CardComponents\ErrorCardComponent;
use Components\FormComponents\SelectionComponent\SelectComponent;
use Components\EntityComponents\CDEClientHComponents;
use Controllers\Actions\ActionsConversation;
use Controllers\ControllerConversation;
use Models\Entities\CDEClientH;
use Models\Entities\ConversationThread;
use Models\EntityLists\ListEtatModel;
use Core\Session\DataHelper;
use Core\Session\UserHelper;

if (!isset($CDEClientH) || !($CDEClientH instanceof CDEClientH)) {
	$errors['view_CDEClient'] = 'the "demande client" is undefined or invalid';
	$CDEClientH = new CDEClientH;
}

if (!isset($States) || !($States instanceof ListEtatModel)) {
	$errors['view_states'] = 'the states is undefined or invalid';
	$States = new ListEtatModel;
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
			<?php ErrorCardComponent::Display('error-card'); ?>
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
			<?php CDEClientHComponents::DisplayDescriptionListDetails($CDEClientH); ?>
		</section>

		
		<section class="flex flex-col items-center 
			bg-gray-100 border rounded-lg px-4 py-6 shadow
			max-w-6xl 
			lg:max-w-4xl
		">
			<?php if ($CDEClientH->Products->IsEmpty()): ?>
				<h4 class="text-2xl">Aucun Produit</h4>
			<?php else: ?>
				<div class="overflow-x-auto mt-8 rounded-lg shadow inline-block align-middle min-w-full">
					<table class="border min-w-full divide-y divide-gray-200">
						<thead class="bg-gray-300">
							<tr>
								<th scope="col" class="px-6 py-3 text-center font-medium text-gray-700 uppercase
									text-2xl 
									lg:text-lg 
								">
									Produit
								</th>

								<th scope="col" class="px-6 py-3 text-center font-medium text-gray-700 uppercase
									text-2xl 
									lg:text-lg 
								">
									Fournisseur
								</th>

								<th scope="col" class="px-6 py-3 text-center font-medium text-gray-700 uppercase
									text-2xl 
									lg:text-lg 
								">
									Quantitée
								</th>

								<th scope="col" class="px-6 py-3 text-center font-medium text-gray-700 uppercase
									text-2xl 
									lg:text-lg 
								">
									Commentaire
								</th>
								
								<th scope="col" class="px-6 py-3 text-center font-medium text-gray-700 uppercase
									text-2xl 
									lg:text-lg 
								">
									Etat
								</th>
							</tr>
						</thead>
						
						
						<tbody class="bg-gray-100 divide-y divide-gray-300">
							<?php foreach ($CDEClientH->Products as $CDEClientL): ?>
								<tr class="odd:bg-gray-200 even:bg-gray-100 hover:bg-blue-100 group ">
									<td class="px-6 py-4 whitespace-nowrap text-start text-pretty hyphens-auto font-medium text-gray-800 " lang="fr">
										<?= $CDEClientL->produit ?? 'indéfini' ?>
									</td>
									
									<td class="px-6 py-4 whitespace-nowrap text-start text-pretty hyphens-auto text-gray-800 ">
										<?= $CDEClientL->Fournisseur ?? 'indéfini' ?>
									</td>
									
									<td class="px-6 py-4 whitespace-nowrap text-center text-pretty hyphens-auto text-gray-800 text-center">
										<?= $CDEClientL->qte ?? 'indéfini' ?>
									</td>
									
									<td class="px-6 py-4 whitespace-nowrap text-start text-pretty hyphens-auto text-gray-800 ">
										<?= $CDEClientL->demandeurComment ?? '' ?>
									</td>
									
									<td class="px-2 whitespace-nowrap text-center text-pretty hyphens-auto text-gray-800 ">
										<?= $CDEClientL->Etat ?? 'indéfini' ?>
									</td>
								</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				</div>
			<?php endif; ?>
		</section>
	</main>
</body>