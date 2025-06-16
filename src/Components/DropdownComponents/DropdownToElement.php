<?php
namespace Components\DropdownComponents;

use Components\DropdownComponents\AbstractDropdownElement;

class DropdownToElement extends AbstractDropdownElement
{
	public const DefaultName = 'Loreipsum';
	public string $id = '';
	public string $name = self::DefaultName;
	public string $target_id = '';

	public function __construct(string $id = '', string $name = self::DefaultName, string $target_id = '') {
		$this->id = $id;
		$this->target_id = ($target_id != '') ? $target_id : $this->id;
		$this->name = $name;
	}

	public static function Display(string $id = '', string $name = self::DefaultName, string $target_id = ''): void {
		?>

		<a href="#<?= $target_id ?? $id ?>" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" 
			<?php if ($id != ''): ?> id="<?= $id ?> <?php endif; ?>
		"><?= $name ?></a>

		<?php
	}

	public function Show(): void {
		?>

		<a href="#<?= $this->target_id ?? $this->id ?>" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" 
			id="<?= $this->id ?>"
		><?= $this->name ?></a>
		
		<?php
	}
}