<?php
namespace Components\FormComponents\InputComponents;

use Components\FormComponents\InputComponents\InputComponent;

class InputFileComponent extends InputComponent
{
	public const FILE_TYPE_ANY_AUDIO = "audio/*";
	public const FILE_TYPE_ANY_VIDEO = "video/*";
	public const FILE_TYPE_ANY_IMAGE = "image/*";

	public bool $is_multiple;

	public static function Display(string $id,  string $class, 
	string $name, bool $is_required = false, bool $is_multiple = false, string ...$file_types): void {
		$file = "";

		if ($file_types != []) {
			$file = "";
			foreach ($file_types as $file_type) {
				$file .= $file_type;
			}
		}
		?>

		<input id="<?= $id ?>" class="<?= $class ?>" type="file" 
				name="<?= $name ?><?php if ($is_multiple): ?>[]<?php endif ?>" 
				accept="<?= $file ?>"  
				<?php if ($is_required): ?> required <?php endif ?>
				<?php if ($is_multiple): ?> multiple <?php endif ?>
				formenctype="multipart/form-data"
		>

		<?php
	}
}