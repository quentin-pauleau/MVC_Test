<?php
namespace Components\EntityComponents;

use Components\ActionComponents\TableActionComponent;
use Components\DescriptionListComponent;
use Controllers\Actions\ActionsSuiviDemandes;
use Controllers\ControllerSuiviDemandes;
use Models\Entities\CDEFourH;
use Models\EntityLists\ListCDEFourH;
use Traits\StaticClass;

class CDEFourHComponents
{
	use StaticClass;

	public static function DisplayTableSuivisDemandes(ListCDEFourH $ListCDEFour, ?int $id_user = null): void {
		?>
		<div class="overflow-x-auto rounded-lg shadow inline-block align-middle min-w-full">
			<table class="min-w-full divide-y divide-gray-200">
				<thead class="bg-gray-300 rounded-lg">
					<tr>
						<th scope="col" class="pl-2 py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Fournisseur
						</th>

						<th scope="col" class="pl-2 py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Demandeur
						</th>

						<th scope="col" class="pl-2 py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Destinataire
						</th>

						<th scope="col" class="pl-2 py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Etat
						</th>

						<th scope="col" class="pl-2 py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Date
						</th>

						<th scope="col" class="pl-2 py-3">
							<!-- Action -->
						</th>
					</tr>
				</thead>
				
				<tbody class="bg-gray-100 divide-y divide-gray-300">
					<?php foreach ($ListCDEFour as $key => $CDEFourH): ?>
						<tr class="odd:bg-gray-200 even:bg-gray-100 hover:bg-blue-100 group/row">
							<td lang="fr" class="pl-2 -py-4 whitespace-nowrap text-start text-pretty hyphens-auto font-medium text-gray-800
								text-lg 
								lg:text-lg 
							">
								<?= $CDEFourH->Fournisseur ?? 'indéfini' ?>
							</td>
				
							<td class="pl-2 py-4 whitespace-nowrap text-start text-pretty hyphens-auto text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= $CDEFourH->Demandeur ?? 'indéfini' ?>
							</td>
				
							<td class="pl-2 py-4 whitespace-nowrap text-start text-pretty hyphens-auto text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= $CDEFourH->Destinataire ?? 'indéfini' ?>
							</td>
							
							<td class="pl-2 py-4 whitespace-nowrap text-center text-pretty hyphens-auto text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= $CDEFourH->Etat ?? 'indéfini' ?>
							</td>

							<td class="pl-2 py-4 whitespace-nowrap text-center text-pretty hyphens-auto text-gray-800
								text-lg 
								lg:text-lg 
							">
								<?= 
									$CDEFourH->CreatedAt != null
									? $CDEFourH->CreatedAt->format('d/m/y')
									: 'indéfini'
								?>
							</td>
							
							<td class="pr-2 py-4 whitespace-nowrap text-end font-medium select-none ">
								<?php TableActionComponent::Display(
									'details',
									'index.php?controller='.ControllerSuiviDemandes::class.'&action='.ActionsSuiviDemandes::DETAILS_CDE_FOURNISSEUR.'&id='.$CDEFourH->id,
									'public/Icons/view.svg',
									'details',
									'emerald',
								); ?>
							
							<?php ConversationComponent::DisplayOpenThreadButton($CDEFourH, $id_user); ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	public static function DisplayTableSuivisDemandeur(ListCDEFourH $ListCDEFour, ?int $id_user = null): void {
		?>
		<div class="overflow-x-auto rounded-lg shadow inline-block align-middle min-w-full">
			<table class="min-w-full divide-y divide-gray-200">
				<thead class="bg-gray-300 rounded-lg">
					<tr>
						<th scope="col" class="pl-2 py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Fournisseur
						</th>

						<th scope="col" class="pl-2 py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Destinataire
						</th>

						<th scope="col" class="pl-2 py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Etat
						</th>

						<th scope="col" class="pl-2 py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Date
						</th>

						<th scope="col" class="pl-2 py-3">
							<!-- Action -->
						</th>
					</tr>
				</thead>
				
				<tbody class="bg-gray-100 divide-y divide-gray-300">
					<?php foreach ($ListCDEFour as $key => $CDEFourH): ?>
						<tr class="odd:bg-gray-200 even:bg-gray-100 hover:bg-blue-100 group/row">
							<td lang="fr" class="pl-2 -py-4 whitespace-nowrap text-start text-pretty hyphens-auto font-medium text-gray-800
								text-lg 
								lg:text-lg 
							">
								<?= $CDEFourH->Fournisseur ?? 'indéfini' ?>
							</td>
				
							<td class="pl-2 py-4 whitespace-nowrap text-start text-pretty hyphens-auto text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= $CDEFourH->Destinataire ?? 'indéfini' ?>
							</td>
							
							<td class="pl-2 py-4 whitespace-nowrap text-center text-pretty hyphens-auto text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= $CDEFourH->Etat ?? 'indéfini' ?>
							</td>

							<td class="pl-2 py-4 whitespace-nowrap text-center text-pretty hyphens-auto text-gray-800
								text-lg 
								lg:text-lg 
							">
								<?= 
									$CDEFourH->CreatedAt != null
									? $CDEFourH->CreatedAt->format('d/m/y')
									: 'indéfini'
								?>
							</td>
							
							<td class="pr-2 py-4 whitespace-nowrap text-end font-medium select-none ">
								<?php TableActionComponent::Display(
									'details',
									'index.php?controller='.ControllerSuiviDemandes::class.'&action='.ActionsSuiviDemandes::DETAILS_CDE_FOURNISSEUR.'&id='.$CDEFourH->id,
									'public/Icons/view.svg',
									'details',
									'emerald',
								); ?>
							
								<?php ConversationComponent::DisplayOpenThreadButton($CDEFourH, $id_user); ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	public static function DisplayTableSuivisDestinataire(ListCDEFourH $ListCDEFour, ?int $id_user = null): void {
		?>
		<div class="overflow-x-auto rounded-lg shadow inline-block align-middle min-w-full">
			<table class="min-w-full divide-y divide-gray-200">
				<thead class="bg-gray-300 rounded-lg">
					<tr>
						<th scope="col" class="pl-2 py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Fournisseur
						</th>

						<th scope="col" class="pl-2 py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Demandeur
						</th>

						<th scope="col" class="pl-2 py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Etat
						</th>

						<th scope="col" class="pl-2 py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Date
						</th>

						<th scope="col" class="pl-2 py-3">
							<!-- Action -->
						</th>
					</tr>
				</thead>
				
				<tbody class="bg-gray-100 divide-y divide-gray-300">
					<?php foreach ($ListCDEFour as $key => $CDEFourH): ?>
						<tr class="odd:bg-gray-200 even:bg-gray-100 hover:bg-blue-100 group/row">
							<td lang="fr" class="pl-2 -py-4 whitespace-nowrap text-start text-pretty hyphens-auto font-medium text-gray-800
								text-lg 
								lg:text-lg 
							">
								<?= $CDEFourH->Fournisseur ?? 'indéfini' ?>
							</td>
				
							<td class="pl-2 py-4 whitespace-nowrap text-start text-pretty hyphens-auto text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= $CDEFourH->Demandeur ?? 'indéfini' ?>
							</td>
							
							<td class="pl-2 py-4 whitespace-nowrap text-center text-pretty hyphens-auto text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= $CDEFourH->Etat ?? 'indéfini' ?>
							</td>

							<td class="pl-2 py-4 whitespace-nowrap text-center text-pretty hyphens-auto text-gray-800
								text-lg 
								lg:text-lg 
							">
								<?= 
									$CDEFourH->CreatedAt != null
									? $CDEFourH->CreatedAt->format('d/m/y')
									: 'indéfini'
								?>
							</td>
							
							<td class="pr-2 py-4 whitespace-nowrap text-end font-medium select-none ">
								<?php TableActionComponent::Display(
									'details',
									'index.php?controller='.ControllerSuiviDemandes::class.'&action='.ActionsSuiviDemandes::DETAILS_CDE_FOURNISSEUR.'&id='.$CDEFourH->id,
									'public/Icons/view.svg',
									'details',
									'emerald',
								); ?>
							
								<?php ConversationComponent::DisplayOpenThreadButton($CDEFourH, $id_user); ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<?php
	}


	public static function DisplayDescriptionListDetails(CDEFourH $CDEFourH): void {
		DescriptionListComponent::Display(
			($CDEFourH->id === null) ? "CDEFourH_description" : "CDEFourH_description_$CDEFourH->id", 
			[
				"Fournisseur" => $CDEFourH->Fournisseur ?? '<i>Non renseigné</i>',
				"Etat" => $CDEFourH->Etat ?? '<i>Non renseigné</i>',
				"Destinataire" => $CDEFourH->Destinataire ?? '<i>Non renseigné</i>',
				"Demandeur" => $CDEFourH->Demandeur ?? '<i>Non renseigné</i>',
				"Commentaire" => $CDEFourH->demandeurComment === '' ? 'Non renseigné' : $CDEFourH->demandeurComment,
			], 
			2
		);
	}


	public static function DisplayDescriptionListDestinataire(CDEFourH $CDEFourH): void {
		DescriptionListComponent::Display(
			($CDEFourH->id === null) ? "CDEFourH_description" : "CDEFourH_description_$CDEFourH->id", 
			[
				"Fournisseur" => $CDEFourH->Fournisseur ?? '<i>Non renseigné</i>',
				"Demandeur" => $CDEFourH->Demandeur ?? '<i>Non renseigné</i>',
				"Commentaire" => $CDEFourH->demandeurComment === '' ? 'Non renseigné' : $CDEFourH->demandeurComment,
			], 
			2
		);
	}


	public static function DisplayDescriptionListDemandeur(CDEFourH $CDEFourH): void {
		DescriptionListComponent::Display(
			($CDEFourH->id === null) ? "CDEFourH_description" : "CDEFourH_description_$CDEFourH->id", 
			[
				"Fournisseur" => $CDEFourH->Fournisseur ?? '<i>Non renseigné</i>',
				"Etat" => $CDEFourH->Etat ?? '<i>Non renseigné</i>',
				"Destinataire" => $CDEFourH->Destinataire ?? '<i>Non renseigné</i>',
				"Commentaire" => $CDEFourH->demandeurComment === '' ? 'Non renseigné' : $CDEFourH->demandeurComment,
			], 
			2
		);
	}
}