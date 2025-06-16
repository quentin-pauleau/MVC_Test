<?php
namespace Components\FormComponents\InputComponents;

use Components\FormComponents\InputComponents\InputComponent;

class InputPasswordComponent extends InputComponent
{

	public string $class = self::DEFAULT_CLASS;

	public string $input_name = "";
	public string $default_value = "";
	public bool $input_required = false;


	public function __construct(string $id = "", string $class = self::DEFAULT_CLASS, 
			string $default_value = "", bool $input_required = false) {
		
		$this->class = $class;
		$this->default_value = $default_value;
		$this->input_required = $input_required;
	}


	public static function Display(string $id, string $class, 
			string $name, bool $is_required = false, 
			string $default_value = ""): void {
		?>

		<input id="<?= $id ?>" class="<?= $class ?>" type="password" name="<?= $name ?>" value="<?= $default_value ?>" 
				<?php if ($is_required): ?> required <?php endif ?>
		>

		<?php
	}
}
