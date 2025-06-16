<?php
namespace Components\DropdownComponents;

use Components\DropdownComponents\AbstractDropdownElement;

class DropdownElement extends AbstractDropdownElement
{
	public const DefaultName = 'Loreipsum';
	public string $id = '';
	public string $name = self::DefaultName;

	public function __construct(string $id = '', string $name = self::DefaultName) {
		$this->id = $id;
		$this->name = $name;
	}

	public static function Display(string $id = '', string $name = self::DefaultName): void {
		?>

		<a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" 
			<?php if ($id != ''): ?> id="<?= $id ?> <?php endif; ?>
		"><?= $name ?></a>

		<?php
	}

	public function Show(): void {
		?>

		<a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" 
			<?php if ($this->id != ''): ?> id="<?= $this->id ?>" <?php endif; ?>
		><?= $this->name ?></a>
		<?php
	}
}