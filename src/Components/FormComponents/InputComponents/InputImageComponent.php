<?php
namespace Components\FormComponents\InputComponents;


	// public const FILE_TYPE_IMAGE = "";
	// public const FILE_TYPE_IMAGE = "capture='user' accept='image/*'";

class InputImageComponent extends InputComponent
{
	public const CAPTURE_ANY = null;
	public const CAPTURE_SELFIE = "capture='user'";
	public const CAPTURE_ENVIRONMENT = "capture='environment'";


	public static function Display(string $id,  string $class, 
	string $name, bool $is_required = false, ?string $capture = "", bool $is_multiple = false): void {
		?>

		<input id="<?= $id ?>" class="<?= $class ?>" type="file" 
				name="<?= $name ?><?php if ($is_multiple): ?>[]<?php endif ?>" 
				<?php if ($capture != null): ?> capture="<?= $capture ?>" <?php endif ?>
				accept="image/*" 
				<?php if ($is_required): ?> required <?php endif ?>
				<?php if ($is_multiple): ?> multiple <?php endif ?>
		>

		<?php
	}
}