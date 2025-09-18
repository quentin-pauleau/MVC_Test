<?php
namespace Modules\DatabaseConnection\QueryBuilder;


class DatabaseQueryBuilder extends AbstractQueryBuilder
{
	
	public function __construct() {}

	public function Show(): DatabaseShowQueryBuilder {
		return new DatabaseShowQueryBuilder;
	}

	public function Select() {
		// return a selection query
	}

	public function Insert() {
		// return an insertion query
	}

	public function Update() {
		// return an update query
	}

	public function Delete() {
		// return a deletion query
	}

	public function Create() {
		// return a creation query
	}

	public function Alter() {
		// return an alteration query
	}

	public function Drop() {
		// return a deletion query
	}
}