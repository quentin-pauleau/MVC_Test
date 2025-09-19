<?php
namespace Modules\DatabaseConnection\QueryBuilder;

use Modules\DatabaseConnection\QueryBuilder\CreateQueryBuilder\CreateQueryBuilder;


class QueryBuilder extends AbstractQueryBuilder
{

	public function Show(): ShowQueryBuilder {
		return new ShowQueryBuilder($this->pdo);
	}

	public function Select() {
		return new SelectionQueryBuilder($this->pdo);
	}

	public function Insert(): InsertionQueryBuilder {
		return new InsertionQueryBuilder($this->pdo);
	}

	public function Update() {
		// return an update query
	}

	public function Delete() {
		// return a deletion query
	}

	public function Create(): CreateQueryBuilder {
		return new CreateQueryBuilder($this->pdo);
	}

	public function Alter() {
		// return an alteration query
	}

	public function Drop() {
		// return a deletion query
	}
}