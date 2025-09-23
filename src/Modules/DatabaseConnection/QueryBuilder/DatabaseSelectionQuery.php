<?php
namespace Modules\DatabaseQueryBuilder;

use Feature\DatabaseQueryBuilder\DatabaseQueryConditionBuilder;
use Feature\DatabaseQueryBuilder\Interface\DatabaseSelectionQueryInterface;
use Modules\DatabaseConnection\QueryBuilder\AbstractQueryBuilder;
use Modules\DatabaseConnection\QueryBuilder\QueryConditionBuilder;

class SelectionQueryBuilder extends AbstractQueryBuilder implements DatabaseSelectionQueryInterface
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
	 * Grouping columns
	 * @var array
	 */
	public array $grouping = [];

	
	/**
	 * Having clause
	 * @var 
	 */
	public $having;

	/**
	 * Ordering columns
	 * @var array<string, bool>
	 */
	public array $ordering = [];


	/**
	 * Limit of rows to select
	 */
	public int|null $limit;


	public function __construct() {}
	public function __tostring(): string {
		return $this->Build();
	}

	#region Query Information
	/**
	 * @return array an array with all selected fields and their aliases
	 */
	public function GetFields(): array {
		return array_duplicate($this->fields);
	}

	/**
	 * @return array an array with all tables names
	 */
	public function GetTables(): array {
		return array_duplicate($this->tables);
	}


	/**
	 * @return array an array with all grouping columns
	 */
	public function GetGrouping(): array {
		return array_duplicate($this->grouping);
	}


	/**
	 * @return array an array with all having columns
	 */
	public function GetHaving(): array {
		$grouping = [];

		foreach ($this->ordering as $field => $isAscending)
			$grouping[$field] = $isAscending ? 'ASC' : 'DESC';
		
		return $grouping;
	}


	/**
	 * @return array an array with all grouping columns
	 */
	public function GetOrdering(): array {
		return array_duplicate($this->ordering);
	}

	#endregion Query Information


	#region Query Boolean Information
	public function IsOrdered(): bool {
		return count($this->ordering) > 0;
	}

	public function IsGrouped(): bool {
		return count($this->grouping) > 0;
	}

	public function HasLimit(): bool {
		return $this->limit !== null;
	}

	#endregion Query Boolean Information



	#region Selection Methods

	/**
	 * 
	 * @param array<string, string> $fields array "alias => field name", if no alias provided the field name is used
	 * @param string|null $table optional table name
	 * @example ['id', 'my_name' => 'name'] will result in SELECT id, name AS my_name
	 * @return self
	 */
	public function Select(array $fields, string|null $table = null): static {
		$prefix = '';
		if ($table != null) {
			$this->tables[$table] = $table;
			$table = "$table.";
		}

		foreach ($fields as $alias => $field)
			$this->fields[$alias] = "{$prefix}{$field}";
		
		return $this;
	}


	/**
	 * 
	 * @param string $table table name
	 * @return self
	 */
	public function SelectAll(string $table): static {
		$this->fields[] = 'table.*';
		$this->tables[$table] = $table;
		
		return $this;
	}


	/**
	 * @param array<string, string> $fields array "alias => field name", if no alias provided the field name is used
	 * @example ['id', 'my_name' => 'name'] will result in SELECT DISTINCT id, DISTINCT name AS s
	 * @param string|null $table optional table name
	 * @return self
	 */
	public function SelectDistinct(array $fields, string|null $table = null): static {
		$prefix = '';
		if ($table != null) {
			$this->tables[$table] = $table;
			$table = "$table.";
		}

		foreach ($fields as $alias => $field)
			$fields[$alias] = "DISTINCT {$prefix}{$field}";

		return $this->Select($fields);
	}

	/**
	 * @param array<string, string> $fields array "alias => field name", if no alias provided the field name is used
	 * @param string|null $table optional table name
	 * @example ['id', 'my_name' => 'name'] will result in SELECT COUNT(id), COUNT(name) AS my_name
	 * @return self
	 */
	public function SelectCount(array $fields, string|null $table = null): static {
		$prefix = '';
		if ($table != null) {
			$this->tables[$table] = $table;
			$table = "$table.";
		}
		
		foreach ($fields as $alias => $field)
			$this->fields[$alias] = "COUNT({$prefix}{$field})";

		return $this->Select($fields);
	}


	/**
	 * @param array<string, string> $fields array "alias => field name", if no alias provided the field name is used
	 * @param string|null $table optional table name
	 * @example ['id', 'my_name' => 'name'] will result in SELECT MAX(id), MAX(price) AS my_price
	 * @return self
	 */
	public function SelectMax(array $fields, string|null $table = null): static {
		$prefix = '';
		if ($table != null) {
			$this->tables[$table] = $table;
			$table = "$table.";
		}

		foreach ($fields as $alias => $field)
			$this->fields[$alias] = "MAX({$prefix}{$field})";

		return $this->Select($fields);
	}


	/**
	 * @param array<string, string> $fields array "alias => field name", if no alias provided the field name is used
	 * @param string|null $table optional table name
	 * @return self
	 */
	public function SelectAvg(array $fields, string|null $table = null): static {
		$prefix = '';
		if ($table != null) {
			$this->tables[$table] = $table;
			$table = "$table.";
		}

		foreach ($fields as $alias => $field)
			$this->fields[$alias] = "AVG({$prefix}{$field})";

		return $this->Select($fields);
	}


	/**
	 * @param array<string, string> $fields array "alias => field name", if no alias provided the field name is used
	 * @param string $table
	 * @return self
	 */
	public function SelectSum(array $fields, string|null $table = null): static {
		$prefix = '';
		if ($table != null) {
			$this->tables[$table] = $table;
			$table = "$table.";
		}

		foreach ($fields as $alias => $field)
			$this->fields[$alias] = "SUM({$prefix}{$field})";

		return $this->Select($fields);
	}

	public function SelectSQL(string $sql): static {
		$this->fields[] = $sql;

		return $this;
	}

	#endregion Selection Methods


	#region Query Building
	public function From(string ...$tables): static {
		foreach ($tables as $table)
			$this->tables[$table] = $table;

		return $this;
	}


	/**
	 * 
	 * @param mixed $condition
	 * @todo implement this method
	 * @return self
	 */
	public function Where(?QueryConditionBuilder $condition = null): static {
		return $this;
	}

	public function OrderBy(string $field, bool $isAscending = true): static {
		$this->ordering[$field] = $isAscending;
		return $this;
	}

	public function GroupBy(string ...$fields): static {
		foreach ($fields as $field)
			if (!in_array($field, $this->grouping))
				$this->grouping[] = $field;

		return $this;
	}


	/**
	 * @todo implement this method
	 * @return self
	 */
	public function Having(): static {
		
		return $this;
	}


	/**
	 * Limit
	 * @param int|null $limit
	 * @throws \Exception
	 * @return self
	 */
	public function Limit(int|null $limit): static {
		if ($limit !== null && $limit < 0)
			throw new \Exception("Invalid limit value, limit must be a positive interger value");

		$this->limit = $limit;
		return $this;
	}
	

	public function Build(): string {
		//*select section
		$query = 'SELECT ';

		foreach($this->fields as $field)
			$query .= "$field,";

		$query = rtrim($query, ',');

		//* from section
		$query .= " FROM ";

		foreach($this->tables as $table)
			$query .= "$table,";

		$query = rtrim($query, ',');


		//* grouping section
		if ($this->IsGrouped()){
			$query .= " GROUP BY ";
			
			foreach($this->grouping as $groupingField)
				$query .= "$groupingField,";

			$query = rtrim($query, ',');

			if ($this->having) {
				$query .= " HAVING ";
			}
			
		}

		//* ordering section
		if ($this->IsOrdered()) {
			foreach($this->ordering as $field => $order)
				$query .= " ORDER BY $field ".($order ? 'ASC' : 'DESC').",";
			
			$query = rtrim($query, ',');
		}

		//* limit section
		if ($this->HasLimit())
			$query .= " LIMIT {$this->limit};";

		return $query;
	}

	public function Execute(): array {
		return [];
	}
}