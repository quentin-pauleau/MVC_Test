<?php
namespace Components\FormComponents\SelectionComponent;

use Components\FormComponents\FormElementComponent;

class SelectComponent extends FormElementComponent
{
	public const DEFAULT_CLASS = "
			border border-gray-400 w-full rounded-lg 
			p-8 text-5xl 
			lg:p-2 lg:text-base 
			".self::BASE_CLASS;

	
	public static function Display(string $id, string $class, string $name, bool $is_required = false): void {
		self::Start($id, $class, $name, $is_required);
		self::End();
	}

	public static function Start(string $id, string $class, string $name, bool $is_required = false, bool $is_disable = false): void {
		?>
		
		<select id="<?= $id ?>" name="<?= $name ?>"
				class="select2 <?= $class ?>" 
				<?php if ($is_required): ?> required <?php endif; ?> 
				<?php if ($is_disable): ?> disabled <?php endif; ?> 
		>
			<!-- Set invalid value by default it will be override if an other option is selected -->
			<option hidden disabled selected></option>
		
		<?php
	}
	
	public static function End(): void {
		?>
		</select>
		
		<?php
	}
	
}