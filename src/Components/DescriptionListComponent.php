<?php
namespace Components;

class DescriptionListComponent 
{
	public static function Display(string $id, array $data = [], int $nb_column = 1): void {
		?>

		<dl id="<?= $id ?>">
			<div class="border border-gray-200 grid grid-cols-<?= $nb_column ?> divide-x divide-y
					odd:bg-gray-100 
					even:bg-gray-50
			">
				<?php foreach ($data as $key => $value): ?>
					<div class="px-4 py-5 grid grid-rows-2 gap-y-2 content-start ">
						<dt class="font-medium text-gray-500 text-4xl lg:text-base ">
							<?= $key ?>
						</dt>
						<dd class="text-gray-900 text-3xl lg:text-base">
							<?= $value ?>
						</dd>
					</div>
				<?php endforeach; ?>
			</div>
		</dl>	

		<?php
	}
}