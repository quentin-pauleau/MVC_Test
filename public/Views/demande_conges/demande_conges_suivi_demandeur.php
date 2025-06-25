<?php 

use Components\ButtonComponents\ButtonComponent;
use Components\CardComponents\ErrorCardComponent;
use Components\EntityComponents\DemandeCongesComponents;
use Core\Session\DataHelper;

?>

<?php require "public/Views/head.php"; ?>

<body class="mx-auto 
	bg-orange-300 
	max-w-6xl 
	lg:max-w-4xl 
">
	<header class="
		sticky z-40
		mb-6 top-0 right-0 left-0 px-8 py-6 gap-x-8 
		flex items-center
		bg-gray-100 border-b-4 border-orange-200 shadow 
		w-full h-auto 
	">
		<div class="w-1/5 flex justify-center">
			<?php ButtonComponent::Display(
				'exit_thread',
				ButtonComponent::RED_CLASS."",
				'Retour',
				DataHelper::Get('return_route')
			); ?>
		</div>

		<div class="w-3/5 flex justify-center">
			<span class="flex flex-col items-center ">
				<h2 id="thread_title" class="text-4xl text-center">Demande congés de <?= $DemandeConges->Demandeur ?></h2>
			</span>
		</div>

		<div class="w-1/5 flex justify-center">
		</div>
	</header>

	<main class="flex flex-col px-8 py-6 gap-y-8 
		bg-orange-200 rounded-lg
		border-1 border-orange-400 
		shadow-sm shadow-orange-500

		lg:bg-orange-200 lg:rounded-lg
		lg:border-1 lg:border-orange-400 
		lg:shadow-sm lg:shadow-orange-500
	">
		<section>
			<?php ErrorCardComponent::Display('errors', $errors); ?>
		</section>


		<section class="bg-white shadow sm:rounded-lg group">
			<div class="bg-gray-300 px-4 py-5 sm:px-6 grid grid-cols-2 items-center">
				<h3 class="leading-6 font-medium text-gray-900
					text-4xl 
					lg:text-lg 
				">
					Details de la demande
				</h3>
			</div>

			<!-- Infos -->
			<?php DemandeCongesComponents::DisplayDescriptionListDemandeur($DemandeConges); ?>
		</section>

		
		<section class="flex flex-col 
			bg-gray-100 border rounded-lg px-10 py-6 shadow
			max-w-6xl 
			lg:max-w-4xl
			gap-y-4
		">
			<h2 class="text-2xl lg:text-3xl font-bold text-center">Commentaire</h2>
			<p class="text-xl lg:text-xl text-left">
				<?= $DemandeConges->comment === '' ? 'Non renseigné' : $DemandeConges->comment ?>
			</p>
		</section>

		<section class="flex flex-col self-center justify-center w-4/5 gap-y-4">
			<?php ButtonComponent::Display(
				'cancel',
				ButtonComponent::RED_CLASS,
				'Annuler la demande',
			); ?>
		</section>
	</main>
</body>


<script>
	document.getElementById('cancel').onclick = CancelDemandeConges;

	async function CancelDemandeConges(e) {
		//* ask confirmation
		if(!confirm('Confirmer l\'annulation de la demande de congés')) {
			e.preventDefault();
			return;
		}

		//* disable modification until the service call ended
		this.disabled = true;

		//* Call the service
		const URL = `src/ServicesAsync/DemandeCongesAsync/AsyncDemandeCongesCancel.php?id=<?= $DemandeConges->id ?>`;

		const HTML = await GetHTML(URL);

		//* handle error
		if (HTML != 'true') {
			console.error(HTML);
			document.getElementById('errors').innerHTML += HTML;

			this.disabled = false;
			e.preventDefault();
			return;
		}

		location.reload();
	}

	
	//* Get the html from a given url
	async function GetHTML(url) {
		const response = await fetch(url, {
			method: "GET",
		});

		if (!response.ok) {
			throw new Error(`Response status: ${response.status}`);
		}

		return response.text();
	}

</script>