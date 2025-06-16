<?php 
namespace Components\CardComponents;

class CardComponent {
	public const DEFAULT_CLASS = "
			flex flex-col h-full shadow justify-between rounded-lg pb-8 mt-3 bg-gray-50 
			p-6 border-gray-600 border-4
			xl:p-8 
			";
	
	public static function Display(string $id = "", string $class = self::DEFAULT_CLASS): void
	{
		self::Start($id, $class);
		self::End();
	}

	public static function Start(string $id = "", string $class = self::DEFAULT_CLASS): void {
		?>

		<div class="flex flex-col justify-center">
			<div <?php if ($id != ""): ?> id="<?= $id ?>" <?php endif ?> class="<?= $class ?>">

		<?php
	}

	public static function End(): void {
		?>

			</div>
		</div>

		<?php
	}
}