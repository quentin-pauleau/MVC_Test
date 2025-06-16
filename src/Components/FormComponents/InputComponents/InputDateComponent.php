<?php
namespace Components\FormComponents\InputComponents;

use Components\FormComponents\InputComponents\InputComponent;
use DateTime;

class InputDateComponent extends InputComponent
{
	public string $class = self::DEFAULT_CLASS;

	public string $input_name = "";
	public string $default_value = "";
	public bool $input_required = false;
	public ?DateTime $Date = null;


	public function __construct(string $id = "", string $class = self::DEFAULT_CLASS, 
			string $name = "", bool $is_required = false, ?DateTime $Date = null)
	{
		parent::__construct($id, $class, $name, $is_required);

		$this->Date = $Date;
	}


	public static function Display(string $id, string $class, 
			string $name, bool $is_required = false, 
			?DateTime $Date = null
	): void
	{
		?>

		<input id="<?= $id ?>" class="<?= $class ?>" type="date" name="<?= $name ?>" 
			
			<?php if ($Date !== null): ?> 
				value="<?= $Date->format('Y-m-d') ?>" 
			<?php endif; ?> 
			
			<?php if ($is_required): ?> required <?php endif ?>
		>

		<?php
	}
}
