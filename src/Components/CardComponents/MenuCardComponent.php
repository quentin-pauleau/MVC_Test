<?php
namespace Components\CardComponents;

class MenuCardComponent
{
	public const DEFAULT_CLASS = "
		flex flex-col h-full shadow justify-between rounded-lg pb-8 mt-3 bg-gray-50 
		p-6 border-gray-600 border-4
		xl:p-8 
	";
	public const DEFAULT_TITLE = "Loreipsum";
	public const DEFAULT_DESCRIPTION = "";

	public static function Display(string $id = "", string $class = self::DEFAULT_CLASS, 
			string $title = self::DEFAULT_TITLE, string $link = "",
			?string $details = null
	): void {
		if ($link === "") {
			$class .= " bg-red-200 cursor-not-allowed";
		} else {
			$class .= " cursor-pointer";
		}
		
		?>
		
		<div class="flex flex-col justify-center">
			<div <?php if ($id != ""): ?> id="<?= $id ?>" <?php endif ?> 
				class="<?= $class ?>"
			>
				<h4 class="font-bold leading-tight text-center 
					text-6xl 
					lg:text-4xl 
				">
					<?= $title ?>
				</h4>

				<?php if ($details !== null): ?>
					<p class="font-bold leading-tight text-center 
						text-4xl 
						lg:text-2xl 
					">
						<?= $details ?>
					</p>
				<?php endif; ?> 
			
				<?php if ($link != ""): ?>
					<script>
						document.getElementById("<?= $id ?>").onclick = (e) => {
							window.location.href = "<?= $link ?>";
						}
					</script>
				<?php endif; ?> 
			</div>
		</div>

		<?php
	}
}