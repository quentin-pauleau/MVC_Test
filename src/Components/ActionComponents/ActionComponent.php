<?php
namespace Components\ActionComponents;



class ActionComponent
{
	public static function Display(string $id, string $link, string $icon_path, string $icon_alt, 
		string $color, bool $is_disable = false, int $size = 5, int $lg_size = 10
	): void {
		?>

		<button type="button" 
			<?php if ($id != ''): ?> id="<?= $id ?>" <?php endif; ?>
			
			onclick="window.location='<?= $link ?>'"
			
			<?php if ($is_disable): ?> disable <?php endif; ?>
			
			class="
				inline-flex items-center rounded-full text-gray-800 border-2 shadow select-none 
				p-5 
				lg:p-5 
				bg-<?= $color ?>-100 border-<?= $color ?>-200 
				hover:bg-<?= $color ?>-200 hover:border-<?= $color ?>-400 
				disabled:opacity-50 disabled:pointer-events-none 
		">
			<img src="<?= $icon_path ?>" alt="<?= $icon_alt ?>" class="size-<?= $size ?> lg:size-<?= $lg_size ?>">
		</button>
		
		<?php
	}
}