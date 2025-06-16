<?php
namespace Components\EntityComponents;

use Components\FormComponents\SelectionComponent\SelectComponent;
use Models\EntityLists\ListFournisseur;

class FournisseurComponents
{
	public static function DisplaySelect(ListFournisseur $ListFournisseur, string $id, string $name, string $label = 'Fournisseur', bool $is_required = false, ?int $defaultId = null): void
	{
		?>
		<div class="flex flex-col items-center w-full">
			<label for="<?= $id ?>" class="block text-gray-700 font-medium mb-2 text-center
				text-2xl 
				lg:text-base 
			"><?= $label ?></label>
			<?php SelectComponent::Start(
				$id,
				SelectComponent::DEFAULT_CLASS,
				$name,
				$is_required
			); ?>
				<?php if(!$is_required): ?><option value=""></option><?php endif; ?>
				<?php foreach ($ListFournisseur as $Fournisseur): ?>
					<option value="<?= $Fournisseur->id ?>" <?php if ($defaultId === $Fournisseur->id): ?> selected <?php endif; ?> ><?= $Fournisseur ?></option>
				<?php endforeach; ?>
			<?php SelectComponent::End(); ?>
		</div>
		<?php
	}
}