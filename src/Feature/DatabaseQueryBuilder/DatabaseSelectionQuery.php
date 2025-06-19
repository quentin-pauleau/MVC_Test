<?php
namespace Utils\Database\QueryBuilder;

use Feature\DatabaseQueryBuilder\Interface\DatabaseSelectionQueryInterface;


class DatabaseSelectionQuery implements DatabaseSelectionQueryInterface
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
	 * 
	 * @var array<string, bool>
	 */
	public array $ordering = [];


	public function Select(string ...$fields): static {
		foreach ($fields as $field)
			if (!in_array($field, $this->fields))
				$this->fields[] = $field;
		
		return $this;
	}

	public function From(string ...$tables): static {
		$this->tables = $tables;
		return $this;
	}


	public function GroupBy(): static {
		return $this;
	}

	public function Having(): static {
		return $this;
	}

	public function Limit(int $limit): static {
		return $this;
	}


	public function Where(): static {
		return $this;
	}


	public function OrderBy(string $field, bool $isAscending = true): static {
		$this->ordering[$field] = $isAscending;
		return $this;
	}

	
	public function Build(): string {
		return '';
	}
}