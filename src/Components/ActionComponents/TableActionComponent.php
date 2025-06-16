<?php
namespace Components\ActionComponents;



class TableActionComponent
{
	public static function Display(string $id, string $link, string $icon_path, string $icon_alt, 
		string $color, bool $is_disable = false
	): void {
		?>

		<button type="button" 
			<?php if ($id != ''): ?> id="<?= $id ?>" <?php endif; ?>
			
			onclick="window.location='<?= $link ?>'"
			
			<?php if ($is_disable): ?> disable <?php endif; ?>
			
			class="
				inline-flex items-center rounded-full text-gray-800 border-2 shadow select-none 
				p-2.5 
				lg:p-3 
				group-odd/row:bg-gray-200 group-odd/row:border-gray-400 
				group-even/row:bg-gray-100 group-even/row:border-gray-400
				group-hover/row:hover:bg-<?= $color ?>-200 group-hover/row:hover:border-<?= $color ?>-400 
				[&:not(:hover)]:group-hover/row:bg-blue-200 [&:not(:hover)]:group-hover/row:border-blue-400 
				disabled:opacity-50 disabled:pointer-events-none 
		">
			<img src="<?= $icon_path ?>" alt="<?= $icon_alt ?>" class="size-200 lg:size-100">
		</button>
		
		<?php
	}
}