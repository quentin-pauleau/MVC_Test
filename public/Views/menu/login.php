<?php use Controllers\ControllerMenu; ?>
<?php use Controllers\Actions\ActionsMenu; ?>

<?php use Components\FormComponents\InputComponents\InputTextComponent ?>
<?php use Components\FormComponents\InputComponents\InputPasswordComponent; ?>
<?php use Components\ButtonComponents\SubmitButtonComponent ?>
<?php use Components\CardComponents\ErrorCardComponent ?>

<?php require "public/Views/head.php"; ?>


<body class="mx-auto
		bg-orange-300 
		max-w-6xl 
		lg:max-w-2xl 
">
	<header class="flex flex-col items-center mx-auto mb-8 mt-8">
		<div class="flex flex-col items-center">
			<img src="public/Icons/logo_xpert.jpg" alt="Logo Xpert" width="500" height="500">
		</div>
	</header>

	<main class="bg-orange-200 rounded-lg px-8 py-6 gap-y-8 
			border-1 border-orange-400 
			shadow-sm shadow-orange-500 
	">
		<div class="flex flex-col items-center">
			<h2 class="text-6xl lg:text-3xl font-medium mb-4">Connexion</h2>
		</div>

		<?php ErrorCardComponent::Display() ?>

		<form method="post" action="?controller=<?= ControllerMenu::class ?>&action=<?= ActionsMenu::LOGIN_PROCESS ?>" class="group">

			<div class="mb-10 lg:mb-4">
				<label for="login" class="block text-gray-700 font-medium mb-2
					text-4xl 
					lg:text-base "
				>Nom</label>

				<?php InputTextComponent::Display(
					"login", 
					InputTextComponent::DEFAULT_CLASS, 
					"login",
					true,
				)?>
			</div>
			
			<div class="mb-10 lg:mb-4">
				<label for="pass" class="block text-gray-700 font-medium mb-2
					text-4xl 
					lg:text-base "
				>Mot de passe</label>

				<?php InputPasswordComponent::Display(
					"pass", 
					InputPasswordComponent::DEFAULT_CLASS, 
					"pass",
					true
				)?>
			</div>
			
			
			<div class="flex flex-col ">
				<?php SubmitButtonComponent::Display(
					"submit", 
					SubmitButtonComponent::EMERALD_CLASS
				); ?>
			</div>
		</form>
	</main>
</body>