<?php
namespace Components\EntityComponents;

use Components\FormComponents\CheckboxComponent;
use Components\FormComponents\RadioComponent;
use Components\FormComponents\SelectionComponent\SelectComponent;
use Models\EntityLists\ListEntity;


class EntityComponents
{

	public static function DisplaySelect(ListEntity $ListEntity, string $id, string $name, string $label, bool $is_required = false, bool $is_disabled = false, ?int $defaultId = null): void {
		?>
		<div class="flex flex-col items-center">
			<label for="<?= $id ?>" class="block text-gray-700 font-medium mb-2 text-center
				text-4xl 
				lg:text-lg
			">
				<?= $label ?>
			</label>
			<?php SelectComponent::Start(
				$id,
				SelectComponent::DEFAULT_CLASS,
				$name,
				$is_required,
				$is_disabled
			); ?>
				<?php if(!$is_required): ?><option value=""></option><?php endif; ?>
				<?php foreach ($ListEntity as $Entity): ?>
					<option value="<?= $Entity->id ?>" <?php if ($defaultId === $Entity->id): ?> selected <?php endif; ?> ><?= $Entity ?></option>
				<?php endforeach; ?>
			<?php SelectComponent::End(); ?>
		</div>
		<?php
	}


	public static function DisplayCheckboxSelection(ListEntity $ListEntity, string $id_prefix, string $name, bool $is_required = false, ?int $defaultEntityId = null): void {
		?>
		<div class="flex flex-wrap lg:mx-2 items-center justify-center ">
			<?php foreach ($ListEntity as $key => $Entity): ?>
				<?php CheckboxComponent::Display(
					$id_prefix.$key, 
					CheckboxComponent::DEFAULT_CLASS, 
					$name, 
					$is_required, 
					$Entity->id === $defaultEntityId, 
					$Entity->id, 
					$Entity
				); ?>
			<?php endforeach; ?>
		</div>
		<?php
	}

	public static function DisplayRadioSelection(ListEntity $ListEntity, string $id_prefix, string $name, bool $is_required = false, ?int $defaultEntityId = null): void {
		?>
		<div class="flex flex-wrap lg:mx-2 items-center justify-center ">
			<?php foreach ($ListEntity as $key => $Entity): ?>
				<?php RadioComponent::Display(
					$id_prefix.$key, 
					RadioComponent::DEFAULT_CLASS, 
					$name, 
					$is_required, 
					$Entity->id === $defaultEntityId, 
					$Entity->id, 
					$Entity
				); ?>
			<?php endforeach; ?>
		</div>
		<?php
	}
}