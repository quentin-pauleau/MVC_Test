<?php
namespace Controllers\Actions;

/**
 * Differents routes used by the {@see \Controllers\ControllerFormCDEFournisseur} class
 */
class ActionsFormCDEFournisseur
{
	// CDE Four General actions
	public const CANCEL_PROCESS = 0;
	public const DETAILS = 1;
	public const CONFIRM_PROCESS = 2;

	// CDE Four H (Demand) forms
	public const CDEFourH_ADD = 11;
	public const CDEFourH_ADD_PROCESS = 12;
	public const CDEFourH_UPDATE = 13;
	public const CDEFourH_UPDATE_PROCESS = 14;

	// CDE Four L (Products) forms
	public const CDEFourL_ADD = 21;
	public const CDEFourL_ADD_PROCESS = 22;
	public const CDEFourL_UPDATE = 23;
	public const CDEFourL_UPDATE_PROCESS = 24;
	public const CDEFourL_REMOVE_PROCESS = 25;
	
	// join file actions
	public const JOIN_FILE_ADD_PROCESS = 32;
}