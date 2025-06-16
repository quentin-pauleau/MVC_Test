<?php
namespace Components\ButtonComponents;

use Components\ButtonComponents\ButtonComponent;

class SubmitButtonComponent extends ButtonComponent
{
	public const DEFAULT_TEXT = "Confirmer";

	public string $method = "POST";

	public static function Display(string $id, string $class = self::DEFAULT_CLASS, string $text = self::DEFAULT_TEXT, string $link = "", 
		bool $is_disabled = false, ?string $confim_text = null, string $method = "POST"
	): void
	{
		?>

		<button id="<?= $id ?>" class="<?= $class ?>" type="submit" 
			<?php if ($link != ""): ?> formaction="<?= $link ?>" <?php endif; ?>
			<?php if ($method != ""): ?> formmethod="<?= $method ?>" <?php endif; ?>
			<?php if ($is_disabled): ?> disabled <?php endif; ?>
		>
			<?= $text ?>
		</button>

		<?php if ($confim_text != null): ?> 
			<script>
				document.getElementById("<?= $id ?>").onclick = (e) => {
					this.disabled = true;
					if(!confirm('<?= $confim_text ?>')) {
						e.preventDefault();
						return;
					}
				}
			</script>
		<?php endif; ?> 

		<?php
	}

	public function Show(): void {
		self::Display(
			$this->id, 
			$this->class, 
			$this->text, 
			$this->link, 
			$this->is_disabled, 
			$this->is_confirmable, 
			$this->method
		);
	}
}