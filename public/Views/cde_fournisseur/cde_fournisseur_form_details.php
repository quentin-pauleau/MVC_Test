<?php

use Components\CardComponents\ErrorCardComponent;
use Components\FormComponents\InputComponents\InputImageComponent;
use Components\FormComponents\InputComponents\InputFileComponent;
use Components\ButtonComponents\ButtonComponent;
use Components\ButtonComponents\SubmitButtonComponent;
use Components\DescriptionListComponent;

use Controllers\Actions\ActionsFormCDEFournisseur;
use Controllers\ControllerFormCDEFournisseur;

use Models\Entities\CDEFourH;

if (!isset($errors) || !is_array($errors)) {
	$errors = [
		'errors' => 'Les erreurs ne sont pas définies'
	];
}

if ($CDEFourH != null && !($CDEFourH instanceof CDEFourH)) {
	$errors['view_CDEFourH'] = 'La demande stock n\'est pas définie ou est invalide';
	$CDEFourH = new CDEFourH;
}
?>

<?php require "public/Views/head.php"; ?>

<body class="mx-auto my-8 
		bg-orange-300 
		max-w-6xl 
		lg:max-w-4xl 
">
	<!-- //TODO: Add an header -->
	<main class="flex flex-col px-8 py-6 gap-y-8 
		lg:bg-orange-200 lg:rounded-lg
		lg:border-1 lg:border-orange-400 
		lg:shadow-sm lg:shadow-orange-500
	">
		<section class="bg-white shadow sm:rounded-lg group">
			<div class="bg-gray-300 px-4 py-5 sm:px-6 grid grid-cols-2 items-center">
				<h3 class="leading-6 font-medium text-gray-900
					text-4xl 
					lg:text-lg 
				">
					Details de la demande
				</h3>
				<button type="button" 
						onclick="window.location='index.php?controller=<?= ControllerFormCDEFournisseur::class ?>&action=<?= ActionsFormCDEFournisseur::CDEFourH_UPDATE ?>'"
						class="inline-flex items-center gap-2 rounded-full text-gray-800 select-none 
						p-2.5
						lg:p-3 
						bg-gray-200 border-2 border-gray-400 
						hover:bg-orange-200 hover:border-orange-400 
						[&:not(:hover)]:group-hover:bg-blue-200 [&:not(:hover)]:group-hover:border-blue-400 
						justify-self-end"
				>
					<img src="public/Icons/edit.svg" alt="Modifier" class="size-200 lg:size-100">
				</button>
			</div>

			<!-- Infos -->
			<?php DescriptionListComponent::Display(
				"CDEFourH_description", 
				[
					"Fournisseur" => $CDEFourH->Fournisseur ?? "",
					"Destinataire" => $CDEFourH->Destinataire ?? "",
					"Commentaire" => $CDEFourH->demandeurComment ?? "",
				], 
				2
			); ?>

			<div class="grid
				grid-cols-2 
				md:grid-cols-3 
				lg:grid-cols-4 
				
				justify-items-center 
				place-items-center
			">
				<?php foreach ($files as $file): ?>
					<img src="<?= $file ?>" alt="Photo" class="">
				<?php endforeach ?>
			</div>
		</section>

		<section>
			<?php ErrorCardComponent::Display('errors', $errors) ?>
			
			<div class="-m-1.5 overflow-x-auto">
				<div class="p-1.5 min-w-full inline-block align-middle">
					<div class="border rounded-lg divide-y divide-gray-200 shadow">
						<table class="min-w-full divide-y divide-gray-200 overflow-hidden">
							<thead class="bg-gray-300">
								<tr>
									<th scope="col" class="px-6 py-3 text-start font-medium text-gray-700 uppercase
										text-2xl 
										lg:text-lg 
									">
										<!-- Action Modifier -->
									</th>
									<th scope="col" class="px-6 py-3 text-start font-medium text-gray-700 uppercase
										text-2xl 
										lg:text-lg 
									">
										Produit
									</th>
									<th scope="col" class="px-6 py-3 text-start font-medium text-gray-700 uppercase
										text-2xl 
										lg:text-lg 
									">
										Quantitée
									</th>
									<th scope="col" class="px-6 py-3 text-end font-medium text-gray-700 uppercase
										text-2xl 
										lg:text-lg 
									">
										<!-- Action Supprimer -->
									</th>
								</tr>
							</thead>
						
							<tbody class="bg-gray-100 divide-y divide-gray-300">
								<?php if ($CDEFourH->ListCDEFourL->IsEmpty()): ?>
									<tr>
										<td class="px-6 py-4 whitespace-nowrap text-center text-pretty font-medium inline-flex items-start ">
											<button type="button" disabled 
													class="inline-flex items-center gap-2 rounded-full text-gray-800 border-2 
													p-2.5 
													lg:p-3 
													disabled:opacity-50 disabled:pointer-events-none select-none 
											">
												<img src="public/Icons/edit.svg" alt="Modifier" class="size-200 lg:size-100">
											</button>
										</td>
										<td class="px-6 py-4 whitespace-nowrap text-pretty hyphens-auto font-medium text-gray-400
											text-xl 
											lg:text-lg 
										">
											Nom du produit
										</td>

										<td class="px-6 py-4 whitespace-nowrap text-pretty hyphens-auto text-gray-400
											text-xl 
											lg:text-lg 
										">
											Quantitée
										</td>

										<td class="px-6 py-4 whitespace-nowrap text-center text-pretty font-medium">
											<button type="button" disabled 
													class="inline-flex items-center gap-2 rounded-full text-gray-800 border-2 
													p-2.5 
													lg:p-3 
													disabled:opacity-50 disabled:pointer-events-none select-none 
											">
												<img src="public/Icons/delete.svg" alt="Supprimer" class="size-200 lg:size-100">
											</button>
											</td>
										</tr>

								<?php else: ?>
									<?php foreach ($CDEFourH->ListCDEFourL as $key => $CDEFourL): ?>
										<?php if ($CDEFourL === null) { continue; } ?>
										<tr class="odd:bg-gray-200 even:bg-gray-100 hover:bg-blue-100 group ">
											<td class="px-6 py-4 whitespace-nowrap text-end font-medium select-none inline-flex items-start ">
												<button type="button" 
														onclick="window.location='index.php?controller=<?= ControllerFormCDEFournisseur::class ?>&action=<?= ActionsFormCDEFournisseur::CDEFourL_UPDATE ?>&produit=<?= $key ?>'"
														class="inline-flex items-center rounded-full text-gray-800 border-2 select-none 
														p-2.5 
														lg:p-3 
														group-odd:bg-gray-200 group-odd:border-gray-400 
														group-even:bg-gray-100 group-even:border-gray-400 
														group-hover:hover:bg-orange-200 group-hover:hover:border-orange-400
														[&:not(:hover)]:group-hover:bg-blue-200 [&:not(:hover)]:group-hover:border-blue-400 
														disabled:opacity-50 disabled:pointer-events-none 
												">
													<img src="public/Icons/edit.svg" alt="Modifier" class="size-200 lg:size-100">
												</button>
											</td>
											
											<td class="px-6 py-4 whitespace-nowrap text-pretty hyphens-auto font-medium text-gray-800 " lang="fr">
												<?= $CDEFourL->produit ?? "" ?>
											</td>
											
											<td class="px-6 py-4 whitespace-nowrap text-pretty hyphens-auto text-gray-800 ">
												<?= $CDEFourL->qte ?? "" ?>
											</td>
												
											<td class="px-6 py-4 whitespace-nowrap text-end font-medium select-none ">
												<button type="button" 
														onclick="window.location='index.php?controller=<?= ControllerFormCDEFournisseur::class ?>&action=<?= ActionsFormCDEFournisseur::CDEFourL_REMOVE_PROCESS ?>&produit=<?= $key ?>'"
														class="inline-flex items-center rounded-full text-gray-800 border-2 select-none 
														p-2.5 
														lg:p-3 
														group-odd:bg-gray-200 group-odd:border-gray-400 
														group-even:bg-gray-100 group-even:border-gray-400
														group-hover:hover:bg-red-200 group-hover:hover:border-red-400 
														[&:not(:hover)]:group-hover:bg-blue-200 [&:not(:hover)]:group-hover:border-blue-400 
														disabled:opacity-50 disabled:pointer-events-none 
												">
													<img src="public/Icons/delete.svg" alt="Supprimer" class="size-200 lg:size-100">
												</button>
											</td>
										</tr>
									<?php endforeach ?>
								<?php endif; ?>
							</tbody>
						</table>

						<!-- Bouton Ajouter -->
						<div class="bg-gray-100 py-1 px-4 flex flex-col items-center">
							<button type="button" 
									onclick="window.location='index.php?controller=<?= ControllerFormCDEFournisseur::class ?>&action=<?= ActionsFormCDEFournisseur::CDEFourL_ADD ?>'"
									class="inline-flex items-center gap-2 rounded-full text-gray-800 select-none 
									py-5 px-20 
									lg:py-2.5 lg:px-12 
									bg-gray-200 border-2 border-gray-400 
									hover:bg-blue-200 hover:border-blue-400 
									disabled:opacity-50 disabled:pointer-events-none "
							>
								<img src="public/Icons/add.svg" alt="Ajouter" class="size-200 lg:size-100">
							</button>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="bg-gray-100 border rounded-lg px-8 py-6 my-8 shadow
					max-w-6xl 
					lg:max-w-4xl
		">
			<div class="flex flex-col items-center mx-auto gap-y-2">
				<h4 class="text-4xl lg:text-xl font-medium">Joindre des fichiers</h4>
			</div>

			<form action="post" enctype="multipart/form-data">

				<!-- 
				<div class="mb-10 lg:mb-4">
					<label for="name" class="block text-gray-700 font-medium mb-2
						text-4xl 
						lg:text-base "
					>Joindre un fichier</label>

					<?php InputFileComponent::Display(
						"joined_files",
						InputFileComponent::DEFAULT_CLASS,
						"joined_files",
						false,
						true
					); ?>
				</div> 
				-->

				<div class="mb-10 lg:mb-4">
					<label for="name" class="block text-gray-700 font-medium mb-2
						text-4xl 
						lg:text-base "
					>Joindre une photo</label>

					<?php InputImageComponent::Display(
						"join_images",
						InputImageComponent::DEFAULT_CLASS,
						"join_images",
						false,
						InputImageComponent::CAPTURE_ANY,
						true
					); ?>
				</div>

				<div class="flex flex-col gap-4 ">
					<?php SubmitButtonComponent::Display(
						"confirm_files", 
						ButtonComponent::INDIGO_CLASS, 
						"Confirmer les fichiers",
						"index.php?controller=".ControllerFormCDEFournisseur::class."&action=".ActionsFormCDEFournisseur::JOIN_FILE_ADD_PROCESS,
						true
					); ?>
				</div>
				
				<script>
					document.getElementById("join_images").onchange = ActivateJoinButton;

					ActivateJoinButton();
					
					function ActivateJoinButton(e = null) {
						var isDisabled = document.getElementById("join_images").files.length === 0;
						document.getElementById("confirm_files").disabled = isDisabled;
					}
				</script>
			</form>
		</section>
		

		<div class="flex flex-col gap-4 ">
			<?php ButtonComponent::Display(
				"confirm", 
				ButtonComponent::EMERALD_CLASS, 
				"Valider",
				"index.php?controller=".ControllerFormCDEFournisseur::class."&action=".ActionsFormCDEFournisseur::CONFIRM_PROCESS
			); ?>

			<?php ButtonComponent::Display(
				"cancel", 
				ButtonComponent::RED_CLASS, 
				"Annuler la demande",
				"index.php?controller=".ControllerFormCDEFournisseur::class."&action=".ActionsFormCDEFournisseur::CANCEL_PROCESS,
				false, 
				"Confirmez l\'annulation"
			); ?>
		</div>
	</main>
</body>