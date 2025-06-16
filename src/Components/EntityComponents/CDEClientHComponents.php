<?php
namespace Components\EntityComponents;

use Components\ActionComponents\TableActionComponent;
use Components\DescriptionListComponent;
use Controllers\Actions\ActionsSuiviDemandes;
use Controllers\ControllerSuiviDemandes;
use Models\Entities\CDEClientH;
use Models\EntityLists\ListCDEClientH;
use Traits\StaticClass;

class CDEClientHComponents
{
	use StaticClass;
	

	public static function DisplayTableSuivisDemandes(ListCDEClientH $ListCDEClient, ?int $id_user = null): void {
		?>
		<div class="overflow-x-auto rounded-lg shadow inline-block align-middle min-w-full">
			<table class="border min-w-full divide-y divide-gray-200">
				<thead class="bg-gray-300">
					<tr>
						<th scope="col" class="pl-2 py-3 text-center font-medium text-gray-700 uppercase 
							text-2xl 
							lg:text-lg 
						">
							Nom Client
						</th>

						<th scope="col" class="py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Demandeur
						</th>
						
						<th scope="col" class="py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Destinataire
						</th>

						<th scope="col" class="py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Etat
						</th>

						<th scope="col" class="py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Date
						</th>

						<th scope="col" class="py-3">
							<!-- Action -->
						</th>
					</tr>
				</thead>
				
				<tbody class="bg-gray-100 divide-y divide-gray-300">
					<?php foreach ($ListCDEClient as $key => $CDEClientH): ?>
						<tr class="odd:bg-gray-200 even:bg-gray-100 hover:bg-blue-100 group/row ">
							<td lang="fr" class="pl-2 py-4 whitespace-nowrap text-start text-pretty hyphens-auto font-medium text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= $CDEClientH->nom_client ?? 'indéfini' ?>
							</td>
							
							<td class="py-4 whitespace-nowrap text-start text-pretty hyphens-auto text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= $CDEClientH->Demandeur ?? 'indéfini' ?>
							</td>
							
							<td class="py-4 whitespace-nowrap text-start text-pretty hyphens-auto text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= $CDEClientH->Destinataire ?? 'indéfini' ?>
							</td>
							
							<td class="py-4 whitespace-nowrap text-center text-pretty hyphens-auto text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= $CDEClientH->Etat ?? 'indéfini' ?>
							</td>
							
							<td class="pr-2 py-4 whitespace-nowrap text-center text-pretty hyphens-auto text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= 
									$CDEClientH->CreatedAt != null
									? $CDEClientH->CreatedAt->format('d/m/y')
									: 'indéfini'
								?>
							</td>
							
							<td class="py-4 whitespace-nowrap text-end font-medium select-none ">
								<?php TableActionComponent::Display(
									"details_$key",
									'index.php?controller='.ControllerSuiviDemandes::class.'&action='.ActionsSuiviDemandes::DETAILS_CDE_CLIENT.'&id='.$CDEClientH->id,
									'public/Icons/view.svg',
									'details',
									'emerald',
								); ?>
								
								<?php ConversationComponent::DisplayOpenThreadButton($CDEClientH, $id_user); ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<?php
	}


	/**
	 * Display informations about each commande/devis client in a table
	 * supposing the user is the "demandeur"
	 * @param \Models\EntityLists\ListCDEClientH $ListCDEClient
	 * @return void
	 */
	public static function DisplayTableSuivisDemandeur(ListCDEClientH $ListCDEClient, int $id_user = null): void {
		?>
		<div class="overflow-x-auto rounded-lg shadow inline-block align-middle min-w-full">
			<table class="border min-w-full divide-y divide-gray-200">
				<thead class="bg-gray-300">
					<tr>
						<th scope="col" class="pl-2 py-3 text-center font-medium text-gray-700 uppercase 
							text-2xl 
							lg:text-lg 
						">
							Nom Client
						</th>

						<th scope="col" class="py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Destinataire
						</th>

						<th scope="col" class="py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Etat
						</th>

						<th scope="col" class="py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Date
						</th>

						<th scope="col" class="py-3">
							<!-- Action -->
						</th>
					</tr>
				</thead>
				
				<tbody class="bg-gray-100 divide-y divide-gray-300">
					<?php foreach ($ListCDEClient as $key => $CDEClientH): ?>
						<tr class="odd:bg-gray-200 even:bg-gray-100 hover:bg-blue-100 group/row ">
							<td lang="fr" class="pl-2 py-4 whitespace-nowrap text-start text-pretty hyphens-auto font-medium text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= $CDEClientH->nom_client ?? 'indéfini' ?>
							</td>

							<td class="py-4 whitespace-nowrap text-start text-pretty hyphens-auto text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= $CDEClientH->Destinataire ?? 'indéfini' ?>
							</td>
							
							<td class="py-4 whitespace-nowrap text-center text-pretty hyphens-auto text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= $CDEClientH->Etat ?? 'indéfini' ?>
							</td>

							<td class="pr-2 py-4 whitespace-nowrap text-center text-pretty hyphens-auto text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= 
									$CDEClientH->CreatedAt != null
									? $CDEClientH->CreatedAt->format('d/m/y')
									: 'indéfini'
								?>
							</td>
							
							<td class="py-4 whitespace-nowrap text-end font-medium select-none ">
								<?php TableActionComponent::Display(
									"details_$key",
									'index.php?controller='.ControllerSuiviDemandes::class.'&action='.ActionsSuiviDemandes::DEMANDEUR_CDE_CLIENT.'&id='.$CDEClientH->id,
									'public/Icons/view.svg',
									'details',
									'emerald',
								); ?>
								
							
								<?php ConversationComponent::DisplayOpenThreadButton($CDEClientH, $id_user); ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	
	/**
	 * Display informations about each commande/devis client in a table
	 * supposing the user is the "destinataire"
	 * @param \Models\EntityLists\ListCDEClientH $ListCDEClient
	 * @return void
	 */
	public static function DisplayTableSuivisDestinataire(ListCDEClientH $ListCDEClient, ?int $id_user = null): void {
		?>
		<div class="overflow-x-auto rounded-lg shadow inline-block align-middle min-w-full">
			<table class="border min-w-full divide-y divide-gray-200">
				<thead class="bg-gray-300">
					<tr>
						<th scope="col" class="pl-2 py-3 text-center font-medium text-gray-700 uppercase 
							text-2xl 
							lg:text-lg 
						">
							Nom Client
						</th>

						<th scope="col" class="py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Demandeur
						</th>

						<th scope="col" class="py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Etat
						</th>

						<th scope="col" class="py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Date
						</th>

						<th scope="col" class="py-3">
							<!-- Action -->
						</th>
					</tr>
				</thead>
				
				<tbody class="bg-gray-100 divide-y divide-gray-300">
					<?php foreach ($ListCDEClient as $key => $CDEClientH): ?>
						<tr class="odd:bg-gray-200 even:bg-gray-100 hover:bg-blue-100 group/row ">
							<td class="pl-2 py-4 whitespace-nowrap text-start text-pretty hyphens-auto font-medium text-gray-800 " lang="fr">
								<?= $CDEClientH->nom_client ?? 'indéfini' ?>
							</td>

							<td class="py-4 whitespace-nowrap text-start text-pretty hyphens-auto text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= $CDEClientH->Demandeur ?? 'indéfini' ?>
							</td>
							
							<td class="py-4 whitespace-nowrap text-center text-pretty hyphens-auto text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= $CDEClientH->Etat ?? 'indéfini' ?>
							</td>

							<td class="pr-2 py-4 whitespace-nowrap text-center text-pretty hyphens-auto text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= 
									$CDEClientH->CreatedAt != null
									? $CDEClientH->CreatedAt->format('d/m/y')
									: 'indéfini'
								?>
							</td>
							
							<td class="py-4 whitespace-nowrap text-end font-medium select-none ">
								<?php TableActionComponent::Display(
									"details_$key",
									'index.php?controller='.ControllerSuiviDemandes::class.'&action='.ActionsSuiviDemandes::DESTINATAIRE_CDE_CLIENT.'&id='.$CDEClientH->id,
									'public/Icons/view.svg',
									'details',
									'emerald',
								); ?>
								<?php ConversationComponent::DisplayOpenThreadButton($CDEClientH, $id_user); ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	
	public static function DisplayDescriptionListDetails(CDEClientH $CDEClientH): void {
		DescriptionListComponent::Display(
			($CDEClientH->id === null) ? "CDEClientH_description" : "CDEClientH_description_$CDEClientH->id", 
			[
				"Client" => $CDEClientH->nom_client ?? '<i>Non renseigné</i>',
				"Type de livraison" => $CDEClientH->TypeLivraison ?? '<i>Non renseigné</i>',
				"Type" => $CDEClientH->GetType(),
				"Etat" => $CDEClientH->Etat ?? '<i>Non renseigné</i>',
				"Destinataire" => $CDEClientH->Destinataire ?? '<i>Non renseigné</i>',
				"Demandeur" => $CDEClientH->Demandeur ?? '<i>Non renseigné</i>',
				"Commentaire" => $CDEClientH->demandeurComment === '' ? 'Non renseigné' : $CDEClientH->demandeurComment,
			], 
			2
		);
	}

	
	public static function DisplayDescriptionListDemandeur(CDEClientH $CDEClientH): void {
		DescriptionListComponent::Display(
			($CDEClientH->id === null) ? "CDEClientH_description" : "CDEClientH_description_$CDEClientH->id", 
			[
				"Client" => $CDEClientH->nom_client ?? '<i>Non renseigné</i>',
				"Type de livraison" => $CDEClientH->TypeLivraison ?? '<i>Non renseigné</i>',
				"Destinataire" => $CDEClientH->Destinataire ?? '<i>Non renseigné</i>',
				"Commentaire" => $CDEClientH->demandeurComment === '' ? 'Non renseigné' : $CDEClientH->demandeurComment,
			], 
			2
		);
	}

	
	public static function DisplayDescriptionListDestinataire(CDEClientH $CDEClientH): void {
		DescriptionListComponent::Display(
			($CDEClientH->id === null) ? "CDEClientH_description" : "CDEClientH_description_$CDEClientH->id", 
			[
				"Client" => $CDEClientH->nom_client ?? '<i>Non renseigné</i>',
				"Type de livraison" => $CDEClientH->TypeLivraison ?? '<i>Non renseigné</i>',
				"Demandeur" => $CDEClientH->Demandeur ?? '<i>Non renseigné</i>',
				"Commentaire" => $CDEClientH->demandeurComment === '' ? 'Non renseigné' : $CDEClientH->demandeurComment,
			], 
			2
		);
	}
}