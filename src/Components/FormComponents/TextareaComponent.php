<?php
namespace Components\FormComponents;

use Components\FormComponents\FormElementComponent;

class TextareaComponent extends FormElementComponent
{
	public const DEFAULT_CLASS = "
			border border-gray-400 w-full rounded-lg 
			p-8 text-5xl 
			lg:p-2 lg:text-base 
			".self::BASE_CLASS;
	

	public static function Display(string $id, string $class, string $name, bool $is_required = false, 
			string $content = "", ?int $nb_rows = null, ?int $nb_columns = null
	): void
	{
		?>
		<textarea id="<?= $id ?>" name="<?= $name ?>" 
				class="<?= $class ?>" 
				<?php if ($nb_rows != null): ?> rows="<?= $nb_rows ?>" <?php endif; ?>
				<?php if ($nb_columns != null): ?> cols="<?= $nb_columns ?>"  <?php endif; ?>
				<?php if ($is_required): ?> required <?php endif; ?>
				><?= $content ?></textarea>

		<?php
	}
}