<?php
namespace Components\FormComponents\InputComponents;

use Components\FormComponents\InputComponents\InputComponent;

class InputNumberComponent extends InputComponent
{
	public static function Display(
		string $id, 
		string $class, 
		string $name, 
		bool $is_required = false, 
		string $default_value = ""): void {
		?>

		<div class="flex flex-row">
			<input type="email" id="<?= $id ?>" name="<?= $name ?>" value="<?= $default_value ?>" 
					class="<?= $class ?>" 
					<?php if ($is_required): ?> required <?php endif; ?>
			>
		</div>
		
		<?php
	}
}