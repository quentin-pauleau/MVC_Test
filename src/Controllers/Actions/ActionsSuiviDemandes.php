<?php
namespace Controllers\Actions;


class ActionsSuiviDemandes {
	//
	public const LIST_ALL = 1;
	public const LIST_DEMANDEUR = 2;
	public const LIST_DESTINATAIRE = 3;

	//
	public const DEMANDEUR_CDE_CLIENT = 11;
	public const DEMANDEUR_CDE_FOURNISSEUR = 12;
	public const DEMANDEUR_DEMANDES_DIVERSES = 13;
	public const DEMANDEUR_DEMANDES_CONGES = 14;

	//
	public const DESTINATAIRE_CDE_CLIENT = 21;
	public const DESTINATAIRE_CDE_FOURNISSEUR = 22;
	public const DESTINATAIRE_DEMANDES_DIVERSES = 23;
	public const DESTINATAIRE_DEMANDES_CONGES = 24;

	//
	public const DETAILS_CDE_CLIENT = 31;
	public const DETAILS_CDE_FOURNISSEUR = 32;
	public const DETAILS_DEMANDES_DIVERSES = 33;
	public const DETAILS_DEMANDES_CONGES = 34;
}