<?php
namespace Components\DropdownComponents;

use Components\DropdownComponents\AbstractDropdownElement;

class DropdownGroup extends AbstractDropdownElement
{
	public string $name = '';

	public function __construct(string $id = '', string $name = '') {
		$this->id = $id;
		$this->name = $name;
	}

	public static function Display(string $id = '', string $name = ''): void {
		?>

		</div>
		<div 
			<?php if ($id != ''): ?> id="<?= $id ?>" <?php endif; ?> 
			class="py-1 <?php if ($name != ''): ?> group/<?= $name ?> <?php endif; ?>" 
			role="none"
		>
		
		<?php
	}

	public function Show(): void {
		?>

		</div>
		<div 
			<?php if ($this->id != ''): ?> id="<?= $this->id ?>" <?php endif; ?> 
			class="py-1 <?php if ($this->name != ''): ?> group/<?= $this->name ?> <?php endif; ?>" 
			role="none"
		>
		
		<?php
	}
}