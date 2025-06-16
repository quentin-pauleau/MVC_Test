<?php
namespace Components\EntityComponents;

use Components\ActionComponents\TableActionComponent;
use Controllers\Actions\ActionsConversation;
use Controllers\ControllerConversation;
use Interfaces\ConversationSubjectInterface;
use Models\Entities\ConversationMessage;

class ConversationComponent
{
	/**
	 * Summary of DisplayCard
	 * @param string|null $id if null use the id of the Message
	 * @return void
	 */
	public static function DisplayCard(ConversationMessage $Message, ?string $id = ""): void {
		?>

		<div <?php if ($id != ""): ?> id="<?= $id ?>" <?php endif ?> 
			class="
				flex flex-col gap-y-2 
				bg-gray-100 border rounded-lg px-8 py-6 my-8 shadow 
				max-w-xl 
				lg:max-w-xl 
			"
		>
			<span>
				<h4 class="
					font-bold leading-tight text-center 
					text-4xl 
					lg:text-2xl 
				">
					<?= $Message->Author ?? 'indéfini' ?>
				</h4>

				<span lang="fr" class="text-end text-pretty hyphens-auto 
					text-4xl 
					lg:text-2xl 
				">
					<?php $Message->CreatedAt->format('d/m/y') ?>
				</span>
			</span>
			
			<div lang="fr" class="text-pretty hyphens-auto my-4 
					text-2xl 
					lg:text-2xl
			">
				<p><?= $Message->content ?? '' ?></p>
			</div>
		</div>

		<?php
	}


	public static function DisplayOpenThreadButton(ConversationSubjectInterface $ConversationSubject, ?int $user_id = null): void {
		if ($ConversationSubject->GetConversationThread() === null) {
			TableActionComponent::Display(
				"new_discution_".$ConversationSubject->GetId(),
				'index.php?controller='.ControllerConversation::class.'&action='.ActionsConversation::INIT.'&subject_type='.$ConversationSubject::GetConversationSubjectType().'&subject_id='.$ConversationSubject->GetId(),
				'public/Icons/chat.svg',
				'Discution',
				'orange',
			);
			return;
		}

		if ($user_id != null && $ConversationSubject->GetConversationThread()->HasNotificationForUser($user_id)) {
			?>

			<button type="button" id="open_discution_$key",
				onclick="window.location='index.php?controller=<?= ControllerConversation::class ?>&action= <?= ActionsConversation::THREAD ?>&id=<?= $ConversationSubject->GetConversationThreadId() ?>&subject_type=<?= $ConversationSubject::GetConversationSubjectType() ?>&subject_id=<?= $ConversationSubject->GetId() ?>'"
				class="
					inline-flex items-center rounded-full text-red-600 border-2 shadow select-none 
					p-2.5 
					lg:p-3 
					group-odd/row:bg-red-200 group-odd/row:border-red-400 
					group-even/row:bg-red-100 group-even/row:border-red-400
					group-hover/row:hover:bg-orange-200 group-hover/row:hover:border-red-400 
					[&:not(:hover)]:group-hover/row:bg-blue-200 [&:not(:hover)]:group-hover/row:border-blue-400 
					disabled:opacity-50 disabled:pointer-events-none 
			">
				<img src="public/Icons/chat.svg" alt="Discution Notifiée !" class="size-200 lg:size-100">
			</button>
			
			<?php
			return;
		}
		
		TableActionComponent::Display(
			"open_discution_".$ConversationSubject->GetId(),
			'index.php?controller='.ControllerConversation::class.'&action='.ActionsConversation::THREAD.'&id='.$ConversationSubject->GetConversationThreadId().'&subject_type='.$ConversationSubject::GetConversationSubjectType().'&subject_id='.$ConversationSubject->GetId(),
			'public/Icons/chat.svg',
			'Discution',
			'orange',
		);
	}
}