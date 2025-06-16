<?php
namespace Components\EntityComponents;

use Components\ActionComponents\TableActionComponent;
use Components\DescriptionListComponent;
use Controllers\Actions\ActionsConversation;
use Controllers\Actions\ActionsSuiviDemandes;
use Controllers\ControllerConversation;
use Controllers\ControllerSuiviDemandes;
use Models\Entities\ConversationThread;
use Models\Entities\DemandeDiverse;
use Models\EntityLists\ListDemandeDiverse;
use Traits\StaticClass;


class DemandeDiverseComponents
{
	use StaticClass;

	public static function DisplayTableSuivisDemandes(ListDemandeDiverse $ListDemandeDiverse, ?int $id_user = null): void {
		?>

		<div class="overflow-x-auto rounded-lg shadow inline-block align-middle min-w-full">
			<table class="border min-w-full divide-y divide-gray-200">
				<thead class="bg-gray-300">
					<tr>
						<th scope="col" class="px-6 py-3 text-start font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Demande
						</th>
						
						<th scope="col" class="px-6 py-3 text-start font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Demandeur
						</th>
						
						<th scope="col" class="px-6 py-3 text-start font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Destinataire
						</th>
						
						<th scope="col" class="px-6 py-3 text-start font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Etat
						</th>
						
						<th scope="col" class="px-6 py-3 text-start font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Date
						</th>
						
						<th scope="col" class="px-6 py-3">
							<!-- Action -->
						</th>
					</tr>
				</thead>
				
				<tbody class="bg-gray-100 divide-y divide-gray-300">
					<?php foreach ($ListDemandeDiverse as $DemandeDiverse): ?>
						<tr class="odd:bg-gray-200 even:bg-gray-100 hover:bg-blue-100 group/row ">

							<td class="px-6 py-4 whitespace-nowrap text-pretty hyphens-auto font-medium text-gray-800 " lang="fr">
								<?= $DemandeDiverse->GetExtrait() ?>
							</td>
							
							<td class="px-6 py-4 whitespace-nowrap text-pretty hyphens-auto text-gray-800 ">
								<?= $DemandeDiverse->Demandeur ?? 'indéfini' ?>
							</td>
							
							<td class="px-6 py-4 whitespace-nowrap text-pretty hyphens-auto text-gray-800 ">
								<?= $DemandeDiverse->Destinataire ?? 'indéfini' ?>
							</td>
							
							<td class="px-6 py-4 whitespace-nowrap text-pretty hyphens-auto text-gray-800 ">
								<?= $DemandeDiverse->Etat ?? 'indéfini' ?>
							</td>

							<td class="px-6 py-4 whitespace-nowrap text-pretty hyphens-auto text-gray-800 ">
								<?= 
									$DemandeDiverse->CreatedAt != null
									? $DemandeDiverse->CreatedAt->format('d/m/y')
									: 'indéfini'
								?>
							</td>
							
							<td class="px-6 py-4 whitespace-nowrap text-end font-medium select-none ">
								<?php TableActionComponent::Display(
									"details_$DemandeDiverse->id",
									'index.php?controller='.ControllerSuiviDemandes::class.'&action='.ActionsSuiviDemandes::DESTINATAIRE_DEMANDES_DIVERSES.'&id='.$DemandeDiverse->id,
									'public/Icons/view.svg',
									'Details',
									'emerald',
								); ?>

								<?php ConversationComponent::DisplayOpenThreadButton($DemandeDiverse, $id_user); ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	public static function DisplayTableSuivisDemandeur(ListDemandeDiverse $ListDemandeDiverse, ?int $id_user = null): void {
		?>

		<div class="overflow-x-auto rounded-lg shadow inline-block align-middle min-w-full">
			<table class="border min-w-full divide-y divide-gray-200">
				<thead class="bg-gray-300">
					<tr>
						<th scope="col" class="px-6 py-3 text-start font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Demande
						</th>
						
						<th scope="col" class="px-6 py-3 text-start font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Destinataire
						</th>
						
						<th scope="col" class="px-6 py-3 text-start font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Etat
						</th>
						
						<th scope="col" class="px-6 py-3 text-start font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Date
						</th>
						
						<th scope="col" class="px-6 py-3">
							<!-- Action -->
						</th>
					</tr>
				</thead>
				
				<tbody class="bg-gray-100 divide-y divide-gray-300">
					<?php foreach ($ListDemandeDiverse as $DemandeDiverse): ?>
						<tr class="odd:bg-gray-200 even:bg-gray-100 hover:bg-blue-100 group/row ">

							<td class="px-6 py-4 whitespace-nowrap text-pretty hyphens-auto font-medium text-gray-800 " lang="fr">
								<?= $DemandeDiverse->GetExtrait() ?>
							</td>
							
							<td class="px-6 py-4 whitespace-nowrap text-pretty hyphens-auto text-gray-800 ">
								<?= $DemandeDiverse->Destinataire ?? 'indéfini' ?>
							</td>
							
							<td class="px-6 py-4 whitespace-nowrap text-pretty hyphens-auto text-gray-800 ">
								<?= $DemandeDiverse->Etat ?? 'indéfini' ?>
							</td>

							<td class="px-6 py-4 whitespace-nowrap text-pretty hyphens-auto text-gray-800 ">
								<?= 
									$DemandeDiverse->CreatedAt != null
									? $DemandeDiverse->CreatedAt->format('d/m/y')
									: 'indéfini'
								?>
							</td>
							
							<td class="px-6 py-4 whitespace-nowrap text-end font-medium select-none ">
								<?php TableActionComponent::Display(
									"details_$DemandeDiverse->id",
									'index.php?controller='.ControllerSuiviDemandes::class.'&action='.ActionsSuiviDemandes::DEMANDEUR_DEMANDES_DIVERSES.'&id='.$DemandeDiverse->id,
									'public/Icons/view.svg',
									'Details',
									'emerald',
								); ?>
								
								<?php ConversationComponent::DisplayOpenThreadButton($DemandeDiverse, $id_user); ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	public static function DisplayTableSuivisDestinataire(ListDemandeDiverse $ListDemandeDiverse, ?int $id_user = null): void {
		?>

		<div class="overflow-x-auto rounded-lg shadow inline-block align-middle min-w-full">
			<table class="border min-w-full divide-y divide-gray-200">
				<thead class="bg-gray-300">
					<tr>
						<th scope="col" class="px-6 py-3 text-start font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Demande
						</th>
						
						<th scope="col" class="px-6 py-3 text-start font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Demandeur
						</th>
						
						<th scope="col" class="px-6 py-3 text-start font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Etat
						</th>
						
						<th scope="col" class="px-6 py-3 text-start font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Date
						</th>
						
						<th scope="col" class="px-6 py-3">
							<!-- Action -->
						</th>
					</tr>
				</thead>
				
				<tbody class="bg-gray-100 divide-y divide-gray-300">
					<?php foreach ($ListDemandeDiverse as $DemandeDiverse): ?>
						<tr class="odd:bg-gray-200 even:bg-gray-100 hover:bg-blue-100 group/row ">

							<td class="px-6 py-4 whitespace-nowrap text-pretty hyphens-auto font-medium text-gray-800 " lang="fr">
								<?= $DemandeDiverse->GetExtrait() ?>
							</td>
							
							<td class="px-6 py-4 whitespace-nowrap text-pretty hyphens-auto text-gray-800 ">
								<?= $DemandeDiverse->Demandeur ?? 'indéfini' ?>
							</td>
							
							<td class="px-6 py-4 whitespace-nowrap text-pretty hyphens-auto text-gray-800 ">
								<?= $DemandeDiverse->Etat ?? 'indéfini' ?>
							</td>

							<td class="px-6 py-4 whitespace-nowrap text-pretty hyphens-auto text-gray-800 ">
								<?= 
									$DemandeDiverse->CreatedAt != null
									? $DemandeDiverse->CreatedAt->format('d/m/y')
									: 'indéfini'
								?>
							</td>
							
							<td class="px-6 py-4 whitespace-nowrap text-end font-medium select-none ">
								<?php TableActionComponent::Display(
									"details_$DemandeDiverse->id",
									'index.php?controller='.ControllerSuiviDemandes::class.'&action='.ActionsSuiviDemandes::DESTINATAIRE_DEMANDES_DIVERSES.'&id='.$DemandeDiverse->id,
									'public/Icons/view.svg',
									'Details',
									'emerald',
								); ?>

								<?php ConversationComponent::DisplayOpenThreadButton($DemandeDiverse, $id_user); ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<?php
	}


	public static function DisplayDescriptionList(DemandeDiverse $DemandeDiverse): void {
		DescriptionListComponent::Display(
			"DemandeDiverse_description", 
			[
				"Demandeur" => $DemandeDiverse->Demandeur ?? '<i>Non renseigné</i>',
				"Destinataire" => $DemandeDiverse->Destinataire ?? '<i>Non renseigné</i>',
				"Commentaire" => $DemandeDiverse->commentaire ?? '',
			], 
			2
		);
	}

	public static function DisplayDescriptionListDemandeur(DemandeDiverse $DemandeDiverse): void {
		DescriptionListComponent::Display(
			"DemandeDiverse_description", 
			[
				"Destinataire" => $DemandeDiverse->Destinataire ?? '<i>Non renseigné</i>',
				"Urgence" => $DemandeDiverse->Urgence ?? '<i>Non renseigné</i>',
				"Etat" => $DemandeDiverse->Etat ?? '<i>Non renseigné</i>',
				"Commentaire" => $DemandeDiverse->commentaire ?? '',
			], 
			2
		);
	}

	public static function DisplayDescriptionListDestinataire(DemandeDiverse $DemandeDiverse): void {
		DescriptionListComponent::Display(
			"DemandeDiverse_description", 
			[
				"Demandeur" => $DemandeDiverse->Demandeur ?? '<i>Non renseigné</i>',
				"Urgence" => $DemandeDiverse->Urgence ?? '<i>Non renseigné</i>',
				"Etat" => $DemandeDiverse->Etat ?? '<i>Non renseigné</i>',
				"Commentaire" => $DemandeDiverse->commentaire ?? '',
			], 
			2
		);
	}
}