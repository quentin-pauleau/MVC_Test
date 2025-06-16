<?php
namespace Controllers\Actions;

/**
 * Differents routes used by the {@see ControllerFormCDEClient} class
 */
class ActionsFormCDEClient
{
	// CDE Client General actions
	public const CANCEL_PROCESS = 0;
	public const DETAILS = 1;
	public const CONFIRM_PROCESS = 2;

	// CDE Client H (Demand) forms
	public const CDEClientH_ADD = 11;
	public const CDEClientH_ADD_PROCESS = 12;
	public const CDEClientH_UPDATE = 13;
	public const CDEClientH_UPDATE_PROCESS = 14;

	// CDE Client L (Products) forms
	public const CDEClientL_ADD = 21;
	public const CDEClientL_ADD_PROCESS = 22;
	public const CDEClientL_UPDATE = 23;
	public const CDEClientL_UPDATE_PROCESS = 24;
	public const CDEClientL_REMOVE_PROCESS = 25;
	
	// join file actions
	public const JOIN_FILE_ADD_PROCESS = 32;
}