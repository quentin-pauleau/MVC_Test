<?php
namespace Controllers\Actions;

/**
 * Differents routes used by the {@see ControllerDebug} class
 */
class ActionsDebug
{
	public const GENERATE_MISSING_UUID_CLIENT = 11;
	public const GENERATE_MISSING_UUID_FOUR = 12;
	public const GENERATE_MISSING_UUID_DEMANDES = 13;
	public const SET_CONVERSATION_THREAD_ID_FROM_0_TO_NULL = 21;
	public const TEST_PDF_DEMAMNDE_CONGES = 24;

	//* Debug Suivis
	public const TEST_SUIVI_CDE_CLIENT = 31;
	public const TEST_SUIVI_CDE_FOURNISSEUR = 32;
	public const TEST_SUIVI_DEMANDE_DIVERSES = 33;
	public const TEST_SUIVI_DEMANDE_CONGES = 34;
}