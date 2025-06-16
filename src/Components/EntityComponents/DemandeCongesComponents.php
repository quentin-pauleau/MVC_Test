<?php
namespace Components\EntityComponents;

use Components\ActionComponents\TableActionComponent;
use Components\DescriptionListComponent;
use Components\FormComponents\CheckboxComponent;
use Controllers\Actions\ActionsDemandeConges;
use Controllers\ControllerDemandeConges;
use Enums\DemandeCongesStates;
use Models\Entities\DemandeConges;
use Models\EntityLists\ListDemandeConges;
use Traits\StaticClass;

class DemandeCongesComponents
{
	use StaticClass;

	
	public static function DisplayTableSuivisDemandes(ListDemandeConges $ListDemandeConges): void {
		?>

		<div class="overflow-x-auto rounded-lg shadow inline-block align-middle min-w-full">
			<table class="min-w-full divide-y divide-gray-200">
				<thead class="bg-gray-300">
					<tr>
						<th scope="col" class="px-6 py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Demandeur
						</th>

						<th scope="col" class="px-6 py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Type
						</th>

						<th scope="col" class="px-6 py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Debut
						</th>

						<th scope="col" class="px-6 py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Fin
						</th>

						<th scope="col" class="px-6 py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Etat
						</th>
						
						
						<th scope="col" class="px-6 py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Action
						</th>
						
					</tr>
				</thead>
				
				<tbody class="bg-gray-100 divide-y divide-gray-300">
					<?php foreach ($ListDemandeConges as $key => $DemandeConges): ?>
						<tr id="DemandeConges_<?= $DemandeConges->id ?>" class="odd:bg-gray-200 even:bg-gray-100 hover:bg-blue-100 group/row ">
							<td class="pl-2 py-4 whitespace-nowrap text-start text-pretty hyphens-auto text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= $DemandeConges->Demandeur ?? 'indéfini' ?>
							</td>

							<td class="pl-2 py-4 whitespace-nowrap text-center text-pretty hyphens-auto text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= $DemandeConges->TypeConges ?? 'indéfini' ?>
							</td>

							<td class="pl-2 py-4 whitespace-nowrap text-center text-pretty hyphens-auto text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= $DemandeConges->GetStart() ?>
							</td>

							<td class="px-6 py-4 whitespace-nowrap text-center text-pretty hyphens-auto text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= $DemandeConges->GetEnd() ?>
							</td>
							
							<td class="px-6 py-4 whitespace-nowrap text-center text-pretty hyphens-auto text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= $DemandeConges->GetStateDescription() ?>
							</td>

							<td class="pr-2 py-4 whitespace-nowrap text-end font-medium select-none ">
								<?php TableActionComponent::Display(
									'details',
									'index.php?controller='.ControllerDemandeConges::class.'&action='.ActionsDemandeConges::DETAILS.'&id='.$DemandeConges->id,
									'public/Icons/view.svg',
									'details',
									'emerald',
								); ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<?php
	}
	
	public static function DisplayTableSuivisDemandesDemandeur(ListDemandeConges $ListDemandeConges): void {
		?>

		<div class="overflow-x-auto rounded-lg shadow inline-block align-middle min-w-full">
			<table class="min-w-full divide-y divide-gray-200">
				<thead class="bg-gray-300">
					<tr>
						<th scope="col" class="px-6 py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Type
						</th>

						<th scope="col" class="px-6 py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Debut
						</th>

						<th scope="col" class="px-6 py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Fin
						</th>

						<th scope="col" class="px-6 py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Etat
						</th>
						
						
						<th scope="col" class="px-6 py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Action
						</th>
						
					</tr>
				</thead>
				
				<tbody class="bg-gray-100 divide-y divide-gray-300">
					<?php foreach ($ListDemandeConges as $key => $DemandeConges): ?>
						<tr class="odd:bg-gray-200 even:bg-gray-100 hover:bg-blue-100 group/row ">
							<td class="pl-2 py-4 whitespace-nowrap text-center text-pretty hyphens-auto text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= $DemandeConges->TypeConges ?>
							</td>

							<td class="pl-2 py-4 whitespace-nowrap text-center text-pretty hyphens-auto text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= $DemandeConges->GetStart() ?>
							</td>

							<td class="px-6 py-4 whitespace-nowrap text-center text-pretty hyphens-auto text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= $DemandeConges->GetEnd() ?>
							</td>
							
							<td class="px-6 py-4 whitespace-nowrap text-center text-pretty hyphens-auto text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= $DemandeConges->state ?? 'indéfini' ?>
							</td>

							<td class="pr-2 py-4 whitespace-nowrap text-end font-medium select-none ">
								<?php TableActionComponent::Display(
									'details',
									'index.php?controller='.ControllerDemandeConges::class.'&action='.ActionsDemandeConges::DETAILS.'&id='.$DemandeConges->id,
									'public/Icons/view.svg',
									'details',
									'emerald',
								); ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<?php
	}


	public static function DisplayDescriptionList(DemandeConges $DemandeConges): void {
		DescriptionListComponent::Display(
			'',
			[
				'Demandeur' => $DemandeConges->Demandeur ?? 'indefini',
				'' => '',
				'Type de conges' => $DemandeConges->TypeConges ?? 'indefini',
				'Etat' => $DemandeConges->state ?? 'indefini',
				'Debut' => $DemandeConges->GetStart(),
				'Fin' => $DemandeConges->GetEnd(),
			],
			2
		);
	}


	public static function DisplayDescriptionListDemandeur(DemandeConges $DemandeConges): void {
		DescriptionListComponent::Display(
			'',
			[
				'Type de conges' => $DemandeConges->TypeConges ?? '',
				'Etat' => $DemandeConges->state ?? 'indefini',
				'Debut' => $DemandeConges->GetStart(),
				'Fin' => $DemandeConges->GetEnd(),
			],
			2
		);
	}


	public static function DisplayStatesSelect(
		string $id_prefix,
		string $name,
		array $PossiblesStates,
		array $RequiredStates = [],
		array $CheckedStates = []
	): void {
		?>
		<div class="flex flex-wrap items-center justify-center">
			<?php foreach ($PossiblesStates as $state): ?>
				<?php CheckboxComponent::Display(
					"$id_prefix.\_$state",
					CheckboxComponent::DEFAULT_CLASS,
					$name,
					in_array($state, $RequiredStates),
					in_array($state, $CheckedStates),
					$state,
					DemandeCongesStates::GetDescription($state)
				); ?>
			<?php endforeach ?>
		</div>
		<?php
	}
}