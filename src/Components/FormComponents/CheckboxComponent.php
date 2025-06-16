<?php
namespace Components\FormComponents;

use Components\FormComponents\FormElementComponent;

class CheckboxComponent extends FormElementComponent
{
	protected const CHECKBOX_CLASS = "h-8 w-8 lg:h-fit lg:w-fit mr-2 border-none";
	public const DEFAULT_CLASS = "
		border-4 block outline-none outline-offset-2 text-4xl py-4 my-2 mx-2 px-4 rounded-lg 
		lg:p-2 lg:text-base lg:border-2 
		hover:outline-indigo-400 hover:outline-2 
		hover:has-[:invalid]:bg-indigo-100 
		hover:has-[:valid]:bg-indigo-100 
		has-[:required:valid]:border-emerald-400 has-[:required:valid]:bg-emerald-50 
		has-[:required:valid:not(:checked)]:border-dotted
		has-[:invalid]:border-red-400 has-[:invalid]:bg-red-50 
		";

	public bool $is_checked = false;
	public string $value = "";
	public string $label = "";

	
	public function __construct(string $id, string $class, 
			string $name = "", bool $is_required = false, 
			bool $is_checked = false, $value = null, string $label = ""
	) {
		parent::__construct($id, $class, $name, $is_required);

		$this->is_checked = $is_checked;
		$this->value = $value;
		$this->label = $label;
	}


	public function Show(): void {
		?>
		
		<div class="px-2 w-1/2">
			<label for="<?= $this->id ?>" class="<?= $this->class ?>">

			<input type="checkbox" id="<?= $this->id ?>" name="<?= $this->name ?>" value="<?= $this->value ?>" 
				class="<?= self::CHECKBOX_CLASS ?>" 
				<?php if ($this->is_checked): ?> checked <?php endif; ?>
				<?php if ($this->is_required): ?> required <?php endif; ?>
			>

			<?= $this->label ?>
			</label>
		</div>

		<?php
	}

	public static function Display(string $id, string $class, 
			string $name, bool $is_required = false, 
			bool $is_checked = false, $value = null, string $label = ""
	): void {
		?>
		
		<label for="<?= $id ?>" class="<?= $class ?>">
			<input type="checkbox" id="<?= $id ?>" name="<?= $name ?>" value="<?= $value ?>" 
					class="<?= self::CHECKBOX_CLASS ?>" 
					<?php if ($is_required): ?> required <?php endif; ?>
					<?php if ($is_checked): ?> checked <?php endif; ?>
			>
			<?= $label ?>
		</label>

		<?php
	}
}