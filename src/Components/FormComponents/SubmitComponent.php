<?php
namespace Components\FormComponents;

/**
 * @deprecated Use the class {link SubmitButtonComponent}
 */
class SubmitComponent {
	protected const BASE_CLASS = "text-white hover:text-gray-700 font-bold py-2 px-4 rounded border-2";
	public const DEFAULT_CLASS = "
			bg-gray-400 border-gray-400 
			hover:bg-gray-200
			".self::BASE_CLASS;
	public const BLUE_CLASS = "
			bg-blue-400 border-blue-400 
			hover:bg-blue-200
			".self::BASE_CLASS;
	
	public const INDIGO_CLASS = "
			bg-indigo-400 border-indigo-400 
			hover:bg-indigo-200
			".self::BASE_CLASS;
	
	public const RED_CLASS = "
			bg-red-400 border-red-400 
			hover:bg-red-200
			".self::BASE_CLASS;
	
	public const ORANGE_CLASS = "
			bg-orange-400 border-orange-400 
			hover:bg-orange-200
			".self::BASE_CLASS;
	
	public const YELLOW_CLASS = "
			bg-yellow-400 border-yellow-400 
			hover:bg-yellow-200
			".self::BASE_CLASS;
	
	
	public const GREEN_CLASS = "
			bg-green-400 border-green-400
			hover:bg-green-200
			".self::BASE_CLASS;


	public static function Display(string $id, string $class, string $text = "Confirmer"): void {
		?>

		<button id="<?= $id ?>" type="submit" class="<?= $class ?>">
			<?= $text ?>
		</button>

		<?php
	}
}