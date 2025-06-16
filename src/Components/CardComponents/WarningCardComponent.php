<?php
namespace Components\CardComponents;

class WarningCardComponent
{
	public static function Display(string $id = '', string $warning = ''): void {
		?>

		<div <?php if ($id === ''): ?> id="<?= $id ?>" <?php endif ?> 
			class="
			bg-amber-100 border-4 border-orange-400 rounded-lg shadow container
			py-6 px-8 mx-auto 
			w-md
			w-4xl
		">
			<p class='text-4xl lg:text-2xl text-center text-orange-600'><?= $warning ?></p>
		</div>

		<?php 
	}
}