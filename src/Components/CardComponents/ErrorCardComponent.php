<?php
namespace Components\CardComponents;

use Core\Session\ErrorHelper;
use Core\Session\UserHelper;

class ErrorCardComponent
{
	public static function Display(string $id = 'errors', ?array $errors = null): void {
		self::Start($id, $errors);
		self::End();
	}

	public static function Start(string $id = 'errors', ?array $errors = null): void {
		if ($errors === null) {
			$errors = ErrorHelper::GetAll(ErrorHelper::TYPE_DEFAULT);

			if (UserHelper::GetUserId() === 1) {
				$errors = array_merge(
					$errors,
					ErrorHelper::GetAll(ErrorHelper::TYPE_DEBUG),
				);
			}
		}
		?>

		<div <?php if ($id != ''): ?> id="<?= $id ?>" <?php endif ?> 
			class="
			bg-amber-100 border-4 border-red-500 rounded-lg shadow container
			py-6 px-8 mx-auto 
			w-md
			w-4xl
			<?php if ($errors === []): ?> hidden <?php endif ?>
			"
		>
			<?php foreach ($errors as $error): ?>
				<?php if (trim($error) === '') { continue; } ?>
				<h3 class='text-4xl lg:text-xl text-center text-red-500'><?= $error ?></h3>
			<?php endforeach ?>
		<?php 
	}

	public static function End(): void {
		?>
		</div>
		<?php
	}
}