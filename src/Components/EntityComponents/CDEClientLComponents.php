<?php
namespace Components\EntityComponents;

use Components\FormComponents\SelectionComponent\SelectComponent;
use Models\EntityLists\ListCDEClientL;
use Models\EntityLists\ListEtatModel;


class CDEClientLComponents
{

	public static function DisplayTable(ListCDEClientL $Products): void {
		?>

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
					<?php foreach ($Products as $Product): ?>
						<tr class="odd:bg-gray-200 even:bg-gray-100 hover:bg-blue-100 group ">
							<td class="px-6 py-4 whitespace-nowrap text-start text-pretty hyphens-auto font-medium text-gray-800 " lang="fr">
								<?= $Product->produit ?? 'indéfini' ?>
							</td>
							
							<td class="px-6 py-4 whitespace-nowrap text-start text-pretty hyphens-auto text-gray-800 ">
								<?= $Product->Fournisseur ?? 'indéfini' ?>
							</td>
							
							<td class="px-6 py-4 whitespace-nowrap text-center text-pretty hyphens-auto text-gray-800 text-center">
								<?= $Product->qte ?? 'indéfini' ?>
							</td>
							
							<td class="px-6 py-4 whitespace-nowrap text-start text-pretty hyphens-auto text-gray-800 ">
								<?= $Product->demandeurComment ?? '' ?>
							</td>
							
							<td class="px-2 whitespace-nowrap text-center text-pretty hyphens-auto text-gray-800 ">
								<?= $Product->Etat ?? 'indéfini' ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<?php
	}



	public static function DisplayTableSuivisDemandeur(ListCDEClientL $ListCDEClientL): void {
		?>
		<div class="overflow-x-auto rounded-lg shadow inline-block align-middle min-w-full">
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
					<?php foreach ($ListCDEClientL as $CDEClientL): ?>
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
		<?php
	}

	
	public static function DisplayTableSuivisDestinataire(ListCDEClientL $ListCDEClientL, ListEtatModel $States): void {
		?>
		<div class="overflow-x-auto rounded-lg shadow inline-block align-middle min-w-full">
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
					<?php foreach ($ListCDEClientL as $CDEClientL): ?>
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
								<?php SelectComponent::Start(
									"state_$CDEClientL->id",
									SelectComponent::DEFAULT_CLASS,
									"state_$CDEClientL->id",
									true
								); ?>
									<option value="<?= $CDEClientL->Etat->id ?>" selected hidden><?= $CDEClientL->Etat ?></option>
									<?php foreach ($States as $State): ?>
										<option value="<?= $State->id ?>"><?= $State ?></option>
									<?php endforeach; ?>
								<?php SelectComponent::End(); ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<?php
	}
}