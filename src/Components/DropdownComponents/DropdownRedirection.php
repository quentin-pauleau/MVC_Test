<?php
namespace Components\DropdownComponents;

use Components\DropdownComponents\AbstractDropdownElement;

class DropdownRedirection extends AbstractDropdownElement
{
	public const DefaultName = 'Loreipsum';
	public const DefaultLink = 'perdu.com';

	public string $id = '';
	public string $name = self::DefaultName;
	public string $link = self::DefaultLink;

	public function __construct(string $id = '', string $name = self::DefaultName, string $link = self::DefaultLink) {
		$this->id = $id;
		$this->name = $name;
		$this->link = ($link != '') ? $link : self::DefaultLink;
	}

	public static function Display(string $id = '', string $name = self::DefaultName, string $link = self::DefaultLink): void {
		?>

		<a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" 
			id="<?= $id ?>"
		><?= $name ?></a>

		<script>
			document.getElementById("<?= $id ?>").onclick = (e) => {
				window.location.href = "<?= $link ?>";
				this.disabled = true;
			}
		</script>
		<?php
	}

	public function Show(): void {
		?>

		<a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" 
			id="<?= $this->id ?>"
		><?= $this->name ?></a>

		<script>
			document.getElementById("<?= $this->id ?>").onclick = (e) => {
				window.location.href = "<?= $this->link ?>";
				this.disabled = true;
			}
		</script>
		<?php
	}
}