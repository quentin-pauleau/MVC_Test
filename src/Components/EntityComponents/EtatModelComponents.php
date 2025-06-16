<?php
namespace Components\EntityComponents;

use Components\FormComponents\CheckboxComponent;
use Components\FormComponents\SelectionComponent\SelectComponent;
use Models\Entities\EtatModel;
use Models\EntityLists\ListEtatModel;


class EtatModelComponents
{
	public static function DisplayCheckboxSelection(ListEtatModel $ListState, string $id_prefix, string $name, bool $is_required = false, ?int $defaultStateId = null): void {
		?>
		<div class="mb-10 lg:mb-4 ">
			<div class="flex flex-wrap lg:mx-2 items-center justify-center ">
				<?php foreach ($ListState as $key => $State): ?>
					<?php CheckboxComponent::Display(
						$id_prefix.$key, 
						CheckboxComponent::DEFAULT_CLASS, 
						$name, 
						$is_required, 
						$State->id === $defaultStateId, 
						$State->id, 
						$State
					); ?>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	public static function DisplaySelect(ListEtatModel $ListState, string $id, string $name, string $label = 'Etat', bool $is_required = false, ?int $defaultId = null): void {
		?>
		<div class="flex flex-col items-center">
			<label for="<?= $id ?>" class="block text-gray-700 font-medium mb-2 text-center
				text-2xl 
				lg:text-base
			"><?= $label ?></label>
			<?php SelectComponent::Start(
				$id,
				SelectComponent::DEFAULT_CLASS,
				$name,
				$is_required,
			); ?>
				<?php if(!$is_required): ?><option value=""></option><?php endif; ?>
				<?php foreach ($ListState as $State): ?>
					<option value="<?= $State->id ?>" <?php if ($defaultId === $State->id): ?> selected <?php endif; ?> ><?= $State ?></option>
				<?php endforeach; ?>
			<?php SelectComponent::End(); ?>
		</div>
		<?php
	}
}