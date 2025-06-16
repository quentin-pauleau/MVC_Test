<?php
namespace Components\EntityComponents;

use Components\FormComponents\SelectionComponent\SelectComponent;
use Models\EntityLists\ListCDEFourL;
use Models\EntityLists\ListEtatModel;

class CDEFourLComponents
{
	public static function DisplayTableSuivisDemandeur(ListCDEFourL $ListCDEFourL): void {
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
							Quantit&eacute;e
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
							&Eacute;tat
						</th>
					</tr>
				</thead>
				
				
				<tbody class="bg-gray-100 divide-y divide-gray-300">
					<?php foreach ($ListCDEFourL as $CDEFourL): ?>
						<tr class="odd:bg-gray-200 even:bg-gray-100 hover:bg-blue-100 group ">
							<td class="px-6 py-4 whitespace-nowrap text-start text-pretty hyphens-auto font-medium text-gray-800 " lang="fr">
								<?= $CDEFourL->produit ?? '' ?>
							</td>
							
							<td class="px-6 py-4 whitespace-nowrap text-center text-pretty hyphens-auto text-gray-800 text-center">
								<?= $CDEFourL->qte ?? '' ?>
							</td>

							<td class="px-6 py-4 whitespace-nowrap text-start text-pretty hyphens-auto text-gray-800 ">
								<?= $CDEFourL->demandeurComment ?? '' ?>
							</td>
							
							<td class="px-2 whitespace-nowrap text-center text-pretty hyphens-auto text-gray-800 ">
								<?= $CDEFourL->Etat ?? 'indéfini' ?>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		
		<?php
	}

	public static function DisplayTableSuivisDestinataire(ListCDEFourL $ListCDEFourL, ListEtatModel $States): void {
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
							Quantit&eacute;e
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
							&Eacute;tat
						</th>
					</tr>
				</thead>
				
				
				<tbody class="bg-gray-100 divide-y divide-gray-300">
					<?php foreach ($ListCDEFourL as $CDEFourL): ?>
						<tr class="odd:bg-gray-200 even:bg-gray-100 hover:bg-blue-100 group ">
							<td class="px-6 py-4 whitespace-nowrap text-start text-pretty hyphens-auto font-medium text-gray-800 " lang="fr">
								<?= $CDEFourL->produit ?? 'indéfini' ?>
							</td>
							
							<td class="px-6 py-4 whitespace-nowrap text-center text-pretty hyphens-auto text-gray-800 text-center">
								<?= $CDEFourL->qte ?? 'indéfini' ?>
							</td>

							<td class="px-6 py-4 whitespace-nowrap text-start text-pretty hyphens-auto text-gray-800 ">
								<?= $CDEFourL->demandeurComment ?? '' ?>
							</td>
							
							<td class="px-2 whitespace-nowrap text-center text-pretty hyphens-auto text-gray-800 ">
								<?php SelectComponent::Start(
									"state_$CDEFourL->id",
									SelectComponent::DEFAULT_CLASS,
									"state_$CDEFourL->id",
									true,
								); ?>
									<option value="<?= $CDEFourL->Etat->id ?>" selected hidden><?= $CDEFourL->Etat ?></option>
									<?php foreach ($States as $State): ?>
										<option value="<?= $State->id ?>"><?= $State ?></option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<?php
	}
}