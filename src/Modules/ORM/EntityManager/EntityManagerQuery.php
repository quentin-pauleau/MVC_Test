<?php
namespace Feature\EntityToDatabase\EntityManager;

use Feature\EntityToDatabase\EntutyManager\EntityManager;
use Core\Database\ListDatabaseQueryParam;

class EntityManagerQuery
{
	protected EntityManager $em;

	/**
	 * @var string[]
	 */
	protected array $SelectedFields;
	protected string $Where;
	protected ListDatabaseQueryParam $params;

	public function __construct(EntityManager $em) {
		$this->em = $em;
	}

	public function SelectField(string ...$fields): static {
		foreach ($fields as $field)
			if (!in_array($field, $this->SelectedFields))
				array_push($this->SelectedFields, $field);

		return $this;
	}

	public function SelectAllField(): static {
		foreach ($this->em->GetFields() as $field)
			if (!in_array($field, $this->SelectedFields))
				array_push($this->SelectedFields, $field);
		
		return $this;
	}

	/**
	 * Left joint on a field based on the foreign key attribute of the property
	 * @param string $table
	 * @param string $on
	 * @return EntityManagerQuery
	 */
	public function JoinForeign(string $table, string $on): static {
		return $this;
	}

	public function Where() {
		
	}


	public function SetLimit(int $limit): static {
		

		return $this;
	}
}