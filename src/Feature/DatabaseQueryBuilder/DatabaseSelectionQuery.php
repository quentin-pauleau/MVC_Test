<?php
namespace Utils\Database\QueryBuilder;


class DatabaseSelectionQuery extends DatabaseQuery
{
	/**
	 * All selected fields as :
	 * 'name' => 'alias'
	 * if alias null, then the name is used
	 */
	public array $fields = [];

	/**
	 * Tables where the selection occures
	 */
	public array $tables = [];

	/**
	 * Tables where the selection occures
	 */
	public array $ordering = [];


	public function Select(): DatabaseSelectionQuery {
		$this->query = 'SELECT ';
		return $this;
	}


	public function AddField(string $field, string $table, ?string $alias = null): DatabaseSelectionQuery {
		$this->tables[$table] = $table;
		$this->fields["$table.$field"] = $alias;
		return $this;
	}


	public function RemoveField(string ...$field): DatabaseSelectionQuery {
		foreach ($field as $f)
			array_splice($this->fields, array_search($f, $this->fields));
		
		return $this;
	}


	public function From(string ...$tables): DatabaseSelectionQuery {
		$this->tables = $tables;
		return $this;
	}


	public function AddTable(string ...$table): DatabaseSelectionQuery {
		array_push($this->tables, ...$table);
		return $this;
	}


	public function RemoveTable(string ...$table): DatabaseSelectionQuery {
		foreach ($table as $t)
			array_splice($this->tables, array_search($t, $this->tables));
		
		return $this;
	}


	public function OrderBy(int $level): DatabaseSelectionQuery {
		$this->tables;
		return $this;
	}

	public function Build(): string {
		$this->query .= implode(', ', array_map(
			function ($field, $alias) {
				return $alias ? "$field AS $alias" : $field;
			},
			array_keys($this->fields),
			$this->fields
		));

		if (!empty($this->tables))
			$this->query .= ' FROM ' . implode(', ', $this->tables);

		if (!empty($this->ordering))
			$this->query .= ' ORDER BY ' . implode(', ', $this->ordering);

		// if (isset($this->limit))
		// 	$this->query .= " LIMIT {$this->limit['start']},{$this->limit['end']}";

		return $this->query;
	}
}