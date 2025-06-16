<?php
namespace Components\ButtonComponents;


class ButtonComponent {
	protected const BASE_CLASS = "
			text-gray-100 hover:text-gray-700 font-bold py-2 rounded border-2 
			text-4xl h-24 px-8
			lg:text-base lg:h-auto lg:px-4 

			disabled:text-gray-700 disabled:bg-gray-300 disabled:border-dashed disabled:cursor-not-allowed
			";
	
	public const DEFAULT_CLASS = "
			text-gray-700 
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
	
	public const PURPLE_CLASS = "
			bg-purple-400 border-purple-400 
			hover:bg-purple-200
			".self::BASE_CLASS;
	
	public const RED_CLASS = "
			bg-red-400 border-red-400 
			hover:bg-red-200
			".self::BASE_CLASS;
	
	public const ORANGE_CLASS = "
			bg-orange-400 border-orange-400 
			hover:bg-orange-200
			".self::BASE_CLASS;
	
	public const AMBER_CLASS = "
			bg-amber-400 border-amber-400 
			hover:bg-amber-200
			".self::BASE_CLASS;
	
	public const YELLOW_CLASS = "
			bg-yellow-400 border-yellow-400 
			hover:bg-yellow-200
			".self::BASE_CLASS;
	
	public const EMERALD_CLASS = "
			bg-emerald-400 border-emerald-400
			hover:bg-emerald-200
			".self::BASE_CLASS;
	
	public const GREEN_CLASS = "
			bg-green-400 border-green-400
			hover:bg-green-200
			".self::BASE_CLASS;
	
	public const CYAN_CLASS = "
			bg-cyan-400 border-cyan-400 
			hover:bg-cyan-200
			".self::BASE_CLASS;

	public const DEFAULT_TEXT = "Loreipsum";

	public string $id;
	public string $class = self::DEFAULT_CLASS;
	public string $text = self::DEFAULT_TEXT;
	public string $link = "";
	public bool $is_disabled = false;
	public bool $is_confirmable = false;


	public function __construct(
		string $id, 
		string $class = self::DEFAULT_CLASS, 
		string $text = self::DEFAULT_TEXT, 
		string $link = "", 
		bool $is_disabled = false, 
		bool $is_confirmable = false
	) {
		$this->id ??= $id;
		$this->class ??= $class;
		$this->text ??= $text;
		$this->link ??= $link;
		$this->is_disabled ??= $is_disabled;
		$this->is_confirmable ??= $is_confirmable;
	}

	public function AddClass(string ...$classes): self {
		foreach ($classes as $class) {
			$this->class .= " $class ";
		}
		return $this;
	}

	public function RemoveClass(string ...$classes): self {
		str_replace($classes, "", $this->class);
		return $this;
	}

	public static function Display(
		string $id, 
		string $class = self::DEFAULT_CLASS, 
		string $text = "", 
		string $link = "", 
		bool $is_disabled = false, 
		?string $confirm_text = null
	): void
	{
		?>

		<button id="<?= $id ?>" class="<?= $class ?>"
			<?php if ($is_disabled): ?> disabled  <?php endif ?>
		>
			<?= $text ?>
		</button>

		<?php if ($link != ""): /* if there is a link add the link logic */ ?>
			<script>
				document.getElementById("<?= $id ?>").onclick = (e) => {
					
					<?php if ($confirm_text != null): ?>
						//* if can be confimed, add the confirmation logic
						if(!confirm('<?= $confirm_text ?>')) {
							e.preventDefault();
							return;
						}
					<?php endif; ?>

					window.location.href = "<?= $link ?>";
					this.disabled = true;
				}
			</script>
			
		<?php elseif ($confirm_text != null): ?> 
			<script>
				document.getElementById("<?= $id ?>").onclick = (e) => {
					if(!confirm('<?= $confirm_text ?>')) {
						e.preventDefault();
						return;
					}

					this.disabled = true;
				}
			</script>
		<?php endif; ?>

		<?php
	}

	public function Show(): void
	{
		self::Display(
			$this->id, 
			$this->class, 
			$this->text, 
			$this->link, 
			$this->is_disabled,
			$this->is_confirmable
		);
	}
}
