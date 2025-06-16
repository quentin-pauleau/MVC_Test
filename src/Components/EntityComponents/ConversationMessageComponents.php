<?php
namespace Components\EntityComponents;

use Components\ActionComponents\ActionComponent;
use Controllers\Actions\ActionsConversation;
use Controllers\ControllerConversation;
use Models\EntityLists\ListConversationMessage;
use Traits\StaticClass;


final class ConversationMessageComponents
{
	use StaticClass;

	public static function DisplayTable(ListConversationMessage $ListMessage): void {
		?>
		<div class="overflow-x-auto rounded-lg shadow inline-block align-middle min-w-full">
			<table class="overflow-hidden border min-w-full divide-y divide-gray-200">
				<thead class="bg-gray-300">
					<tr>
						<th scope="col" class="pl-2 py-3 text-center font-medium text-gray-700 uppercase 
							text-2xl 
							lg:text-lg 
						">
							Conversation
						</th>

						<th scope="col" class="py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Auteur
						</th>

						<th scope="col" class="py-3 text-center font-medium text-gray-700 uppercase
							text-2xl 
							lg:text-lg 
						">
							Extrait
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
					<?php foreach ($ListMessage as $key => $Message): ?>

						<tr id="message-<?= $Message->id ?>" class="odd:bg-gray-200 even:bg-gray-100 hover:bg-blue-100 group/row ">
							<td lang="fr" class="pl-2 py-4 whitespace-nowrap text-start text-pretty hyphens-auto font-medium text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= $Message->Thread ?? 'indéfini' ?>
							</td>
							
							<td class="py-4 whitespace-nowrap text-start text-pretty hyphens-auto text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= $Message->Author ?? 'indéfini' ?>
							</td>
							
							<td class="py-4 whitespace-nowrap text-start text-pretty hyphens-auto text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= $Message->GetExtrait() ?? 'indéfini' ?>
							</td>

							<td class="pr-2 py-4 whitespace-nowrap text-center text-pretty hyphens-auto text-gray-800 
								text-lg 
								lg:text-lg 
							">
								<?= 
									$Message->CreatedAt != null
									? $Message->CreatedAt->format('d/m/y')
									: 'indéfini'
								?>
							</td>
							
							<td class="py-4 whitespace-nowrap text-end font-medium select-none ">
								<?php ActionComponent::Display(
									"details_$key",
									'index.php?controller='.ControllerConversation::class.'&action='.ActionsConversation::THREAD.'&id='.$Message->id_thread,
									'public/Icons/chat.svg',
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

}