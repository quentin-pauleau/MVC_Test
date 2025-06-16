<?php
namespace Components\FormComponents\InputComponents;

use Components\FormComponents\InputComponents\InputComponent;

class InputNumberComponent extends InputComponent
{
	// public const DEFAULT_CLASS = "
	// 		border border-gray-400 w-full rounded-lg 
	// 		p-8 text-5xl 
	// 		lg:p-2 lg:text-base 
	// 		focus:outline-none focus:border-blue-400 
	// 		invalid:border-red-400 
	// 		";
	
	public string $default_value = "";

	public static function Display(
		string $id, 
		string $class, 
		string $name, 
		bool $is_required = false, 
		string $default_value = "", 
		?int $min = null,
		?int $max = null,
		?int $step = null): void {
		
		?>

		<div class="flex flex-row">
			<input type="number"  id="<?= $id ?>" name="<?= $name ?>" value="<?= $default_value ?>" 
					<?php if ($min != null): ?> min="<?= $min ?>" <?php endif; ?>
					<?php if ($max != null): ?> max="<?= $max ?>" <?php endif; ?>
					<?php if ($step != null): ?> step="<?= $step ?>" <?php endif; ?>
					class="<?= $class ?>" 
					<?php if ($is_required): ?> required <?php endif; ?>
			>

			<!-- <script>
				var el = document.getElementById("<?= $id ?>");
				el.onkeydown = (e) => {
					const el = e.target;
					el.value = el.value.replace(/[^0-9]/g, "");
				}

				el.onkeyup = el.onkeydown;
			</script> -->
		</div>
		
		<?php
	}
}