<?php

use Components\CardComponents\MenuCardComponent;
use Components\ButtonComponents\ButtonComponent;

use Controllers\Actions\ActionsDemandeConges;
use Controllers\Actions\ActionsFormDemandeConges;
use Controllers\ControllerDemandeConges;
use Controllers\ControllerFormDemandeConges;
use Models\Entities\User;
use Utils\Session\UserHelper;

use Controllers\ControllerFormCDEClient;
use Controllers\Actions\ActionsFormCDEClient;

use Controllers\ControllerFormCDEFournisseur;
use Controllers\Actions\ActionsFormCDEFournisseur;

use Controllers\ControllerFormDemandeDiverse;
use Controllers\Actions\ActionsFormDemandeDiverse;

use Controllers\ControllerMenu;
use Controllers\Actions\ActionsMenu;

use Controllers\ControllerSuiviDemandes;
use Controllers\Actions\ActionsSuiviDemandes; 

?>


<?php require "public/Views/head.php"; ?>


<body class="flex flex-col mx-auto my-8
		bg-orange-300 
		max-w-6xl 
		lg:max-w-full 
">
	<header class="flex flex-col items-center mx-auto mb-8">
		<div class="flex flex-col items-center">
			<img src="public/Icons/logo_xpert.jpg" alt="Logo Xpert" width="500" height="500">
		</div>
	</header>

	<main class="bg-orange-200 rounded-lg px-8 py-6 flex flex-col gap-y-8 lg:mx-60
		border-1 border-orange-400 
		shadow-sm shadow-orange-500 
	">

		<!-- Commandes fournisseur -->
		<section class="flex flex-col w-full gap-y-8 p6">
			<h4 class="font-bold text-center
				text-8xl 
				lg:text-6xl 
			">
				Notifications
			</h4>
			<article class="flex flex-col items-center self-center
				bg-gray-100 border rounded-lg px-4 py-6 shadow
				w-full
			">
				<details class="group/list flex flex-col group-open/list:gap-y-8 w-full">
					<summary class="flex flex-col items-center font-medium cursor-pointer list-none px-20 group-open/list:mb-8">
						<!-- Header of the summary -->
						<span class="flex">
							<span id="notifications_count" class="text-4xl relative bottom-2">
								Messages
							</span>

							<span class="transition group-open/list:rotate-180 group-open/list:bottom-2 group-open/list:relative mx-2">
								<svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24">
									<path d="M6 9l6 6 6-6"></path>
								</svg>
							</span>
						</span>
					</summary>

					<!-- Table -->
					<div id="notifications_table">
						<!-- Async Call -->
					</div>
				</details>
			</article>
		</section>

		<?php if (UserHelper::hasAnyPermission(UserHelper::GetFormPermissions())): ?>
			<section>
				<h4 class="font-bold text-center
					text-8xl 
					lg:text-6xl 
				">
					Formulaires
				</h4>
				<div id="menu-button" class="grid gap-x-8 gap-y-4 p-6 
					grid-cols-1 
					lg:grid-cols-2 
				">
					<?php if (UserHelper::hasPermission(UserHelper::PERMISSIONS_FORM_CDE_CLIENT)): ?>
						<?php MenuCardComponent::Display(
							'card_cde_cli', 
							MenuCardComponent::DEFAULT_CLASS, 
							'COMMANDES / DEVIS CLIENTS', 
							'index.php?controller='.ControllerFormCDEClient::class.'&action='.ActionsFormCDEClient::CDEClientH_ADD
						); ?>
					<?php endif; ?>
					

					<?php if (UserHelper::hasPermission(UserHelper::PERMISSIONS_FORM_CDE_FOURNISSEUR)): ?>
						<?php MenuCardComponent::Display(
							'card_cde_four', 
							MenuCardComponent::DEFAULT_CLASS, 
							'COMMANDES FOURNISSEURS',
							'index.php?controller='.ControllerFormCDEFournisseur::class.'&action='.ActionsFormCDEFournisseur::CDEFourH_ADD
						); ?>
					<?php endif; ?>

					<?php if (UserHelper::hasPermission(UserHelper::PERMISSIONS_FORM_DEM_DIVERSES)): ?>
						<?php MenuCardComponent::Display(
							"card_dem_diverses", 
							MenuCardComponent::DEFAULT_CLASS, 
							"DEMANDES DIVERSES", 
							'index.php?controller='.ControllerFormDemandeDiverse::class.'&action='.ActionsFormDemandeDiverse::DEMANDE_ADD,
						); ?>
					<?php endif; ?>

					<?php if (UserHelper::hasPermission(UserHelper::PERMISSIONS_FORM_DEM_CONGES)): ?>
						<?php MenuCardComponent::Display(
							"card_dem_conges", 
							MenuCardComponent::DEFAULT_CLASS, 
							"DEMANDES DE CONGES", 
							'index.php?controller='.ControllerFormDemandeConges::class.'&action='.ActionsFormDemandeConges::DEMANDE_ADD,
						); ?>
					<?php endif; ?>
				</div>
			</section>
		<?php endif; ?>


		<?php if (UserHelper::hasAnyPermission(UserHelper::GetSuivisPermissions())): ?>
			<section>
				<h4 class="font-bold text-center 
					text-8xl 
					lg:text-6xl 
				">
					Suivi des Demandes
				</h4>
				<div id="menu-button" class="grid gap-x-8 gap-y-4 p-6 
					grid-cols-1 
					lg:grid-cols-2 
				">
					<?php if (UserHelper::GetUser()->IsAdmin()): ?>
						<?php MenuCardComponent::Display(
							'suivi_demandes', 
							MenuCardComponent::DEFAULT_CLASS, 
							'(ADMIN) TOUTES LES DEMANDES', 
							'index.php?controller='.ControllerSuiviDemandes::class.'&action='.ActionsSuiviDemandes::LIST_ALL,
						); ?>

						<div></div>
					<?php endif; ?>

					<?php if (UserHelper::hasPermission(UserHelper::PERMISSIONS_SUIVIS_MES_DEMANDES)): ?>
						<?php MenuCardComponent::Display(
							'suivi_mes_demandes', 
							MenuCardComponent::DEFAULT_CLASS, 
							'MES DEMANDES', 
							'index.php?controller='.ControllerSuiviDemandes::class.'&action='.ActionsSuiviDemandes::LIST_DEMANDEUR,
							''
						); ?>

						<?php MenuCardComponent::Display(
							'suivi_demandes_a_realiser', 
							MenuCardComponent::DEFAULT_CLASS, 
							'DEMANDES A REALISER', 
							'index.php?controller='.ControllerSuiviDemandes::class.'&action='.ActionsSuiviDemandes::LIST_DESTINATAIRE,
							''
						); ?>

						<?php MenuCardComponent::Display(
							'suivi_demandes_conges', 
							MenuCardComponent::DEFAULT_CLASS, 
							'MES DEMANDES DE CONGES', 
							'index.php?controller='.ControllerDemandeConges::class.'&action='.ActionsDemandeConges::LIST_DEMANDEUR,
							''
						); ?>
					<?php endif; ?>

					<?php if (
						UserHelper::GetUser()->IsDirector()
						|| UserHelper::GetUser()->IsAdmin()
					): ?>
						<?php MenuCardComponent::Display(
							'demandes_conges_a_valider', 
							MenuCardComponent::DEFAULT_CLASS, 
							'DEMANDES DE CONGES A VALIDER', 
							'index.php?controller='.ControllerDemandeConges::class.'&action='.ActionsDemandeConges::LIST_DIRECTION,
							''
						); ?>
					<?php endif; ?>
				</div>
			</section>
		<?php endif; ?>
		
		
		<div class="flex justify-center px-6">
			<?php ButtonComponent::Display(
				'disconnect',
				ButtonComponent::RED_CLASS.' lg:w-4/5',
				'Déconnexion',
				'index.php?controller='.ControllerMenu::class.'&action='.ActionsMenu::LOGOUT_PROCESS
			); ?>
		</div>
	</main>
</body>


<script>
	// delay function
	const delay = ms => new Promise(res => setTimeout(res, ms));
	
	// load the conversation thread
	document.addEventListener("DOMContentLoaded", function() {
		LoadMessageNotifs();
		LoadDemandeCountDemandeur();
		LoadDemandeCountDestinataire();
	});


	//* Load and update the messages notifications
	async function LoadMessageNotifs() {
		const DELAY_AMOUNT = 30_000
		
		const API_URL = `src/ServicesAsync/ConversationAsync/AsyncConversationNotifications.php?id_user=<?= UserHelper::GetUserId() ?>`;
		const DIV_TABLE = document.getElementById('notifications_table');
		const DIV_COUNT = document.getElementById('notifications_count');
		
		if (DIV_TABLE == null && DIV_COUNT == null) {
			throw new Error(`Can't find the table div or count div`);
		}

		while(true) {
			let html = await GetHTML(API_URL);

			DIV_TABLE.innerHTML = html;

			let count = document.querySelectorAll('[id^="message-"]').length;

			switch (count) {
				case 0:
					DIV_COUNT.innerHTML = 'Vous n\'avez aucun messages';
					break;

				case 1:
					DIV_COUNT.innerHTML = `Vous avez 1 message`;
					break;

				default:
					DIV_COUNT.innerHTML = `Vous avez ${count} messages`;
					break;
			}

			await delay(DELAY_AMOUNT);
		}
	}


	//* Load and update the demandes count (demandeur)
	async function LoadDemandeCountDemandeur() {
		const DELAY_AMOUNT = 30_000
		
		const API_URL = `src/ServicesAsync/SuivisDemandeAsync/AsyncCountAllDemande.php?id_demandeur=<?= UserHelper::GetUserId() ?>`;
		const DIV = document.querySelector('#suivi_mes_demandes p');
		
		if (DIV == null) {
			throw new Error(`Can't find the div`);
		}

		while(true) {
			let count = await GetHTML(API_URL);

			switch (count) {
				case 0:
					DIV.innerHTML = 'Vous n\'avez aucune demandes';
					break;

				case 1:
					DIV.innerHTML = `Vous avez 1 demande`;
					break;

				default:
					DIV.innerHTML = `Vous avez ${count} demandes`;
					break;
			}

			await delay(DELAY_AMOUNT);
		}
	}


	
	//* Load and update the demandes count (destinataire)
	async function LoadDemandeCountDestinataire() {
		const DELAY_AMOUNT = 30_000
		
		const API_URL = `src/ServicesAsync/SuivisDemandeAsync/AsyncCountAllDemande.php?id_destinataire=<?= UserHelper::GetUserId() ?>`;
		const DIV = document.querySelector('#suivi_demandes_a_realiser > p');
		
		if (DIV == null) {
			throw new Error(`Can't find the div`);
		}

		while(true) {
			let count = await GetHTML(API_URL);

			switch (count) {
				case 0:
					DIV.innerHTML = 'Vous n\'avez aucune demandes';
					break;

				case 1:
					DIV.innerHTML = `Vous avez 1 demande`;
					break;

				default:
					DIV.innerHTML = `Vous avez ${count} demandes`;
					break;
			}

			await delay(DELAY_AMOUNT);
		}
	}


	//* Load and update the demandes conges count (demandeur)
	async function LoadDemandeCongesCountDemandeur() {
		const DELAY_AMOUNT = 30_000
		
		const API_URL = `src/ServicesAsync/AsyncDemandeConges/AsyncCountDemandeConges.php?id_demandeur=<?= UserHelper::GetUserId() ?>`;
		const DIV = document.querySelector('#suivi_demandes_conges > p');
		
		if (DIV == null) {
			throw new Error(`Can't find the div`);
		}

		while(true) {
			let count = await GetHTML(API_URL);

			switch (count) {
				case 0:
					DIV.innerHTML = 'Vous n\'avez aucune demande conges en cours';
					break;

				case 1:
					DIV.innerHTML = `Vous avez 1 demande conges en cours`;
					break;

				default:
					DIV.innerHTML = `Vous avez ${count} demandes conges en cours`;
					break;
			}

			await delay(DELAY_AMOUNT);
		}
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