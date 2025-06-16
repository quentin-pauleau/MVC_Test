<?php
namespace Components\FormComponents;

use Components\FormComponents\FormElementComponent;

class RadioComponent extends FormElementComponent
{
	protected const RADIO_CLASS = "h-8 w-8 lg:h-fit lg:w-fit mr-2 border-none";
	public const DEFAULT_CLASS = "
		border-4 block outline-none outline-offset-2 text-4xl py-4 my-8 px-4 rounded-lg 
		lg:p-2 lg:text-base lg:border-2 
		hover:outline-indigo-400 hover:outline-2 
		hover:has-[:invalid]:bg-indigo-100 
		hover:has-[:valid]:bg-indigo-100 
		has-[:required:valid]:border-emerald-400 has-[:required:valid]:bg-emerald-50 
		has-[:required:valid:not(:checked)]:border-dotted
		has-[:invalid]:border-red-400 has-[:invalid]:bg-red-50 
		";

	public bool $is_selected = false;
	public string $value = "";
	public string $label = "";

	
	public function __construct(string $id, string $class, 
			string $name = "", bool $is_required = false, 
			bool $is_selected = false, $value = null, string $label = ""
	) {
		parent::__construct($id, $class, $name, $is_required);

		$this->is_selected = $is_selected;
		$this->value = $value;
		$this->label = $label;
	}


	public function Show(): void {
		?>
		
		<div class="px-2 w-1/2">
			<label for="<?= $this->id ?>" class="<?= $this->class ?>">

			<input type="radio" id="<?= $this->id ?>" name="<?= $this->name ?>" value="<?= $this->value ?>" 
				class="<?= self::RADIO_CLASS ?>" 
				<?php if ($this->is_selected): ?> selected <?php endif; ?>
				<?php if ($this->is_required): ?> required <?php endif; ?>
			>

			<?= $this->label ?>
			</label>
		</div>

		<?php
	}

	public static function Display(string $id, string $class, 
			string $name, bool $is_required = false, 
			bool $is_selected = false, $value = null, string $label = ""
	): void {
		?>
		
		<label for="<?= $id ?>" class="<?= $class ?>">
			<input type="radio" id="<?= $id ?>" name="<?= $name ?>" value="<?= $value ?>" 
				class="<?= RadioComponent::RADIO_CLASS ?>" 
				<?php if ($is_required): ?> required <?php endif; ?>
				<?php if ($is_selected): ?> checked <?php endif; ?>
			>
			<?= $label ?>
		</label>

		<?php
	}
}