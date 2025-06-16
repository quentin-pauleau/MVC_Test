<?php
namespace Components\FormComponents\InputComponents;

use Components\FormComponents\InputComponents\InputComponent;

class InputTextComponent extends InputComponent
{

	// public const DEFAULT_CLASS = "
	// 	border border-gray-400 w-full rounded-lg 
	// 	p-8 text-5xl 
	// 	lg:p-2 lg:text-base 
	// 	focus:outline-none focus:border-blue-400 
	// 	invalid:border-red-400 
	// 	";

	public string $class = self::DEFAULT_CLASS;

	public string $input_name = "";
	public string $default_value = "";
	public bool $input_required = false;


	public function __construct(string $id = "", string $class = self::DEFAULT_CLASS, 
			string $name = "",  string $default_value = "", bool $is_required = false)
	{
		
		parent::__construct($id, $class, $name, $is_required);
	}


	public static function Display(string $id, string $class, 
			string $name, bool $is_required = false, 
			string $default_value = ""): void {
		?>

		<input id="<?= $id ?>" class="<?= $class ?>" type="text" name="<?= $name ?>" value="<?= $default_value ?>" 
				<?php if ($is_required): ?> required <?php endif ?>
		>

		<?php
	}
}
