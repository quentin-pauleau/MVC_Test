<?php
namespace Feature\DatabaseQueryBuilder\Interface;


interface DatabaseSelectionQueryInterface extends DatabaseQueryInterface
{
	public function Select(string ...$field): static;
	public function From(string ...$table): static;
	public function Where(): static;
	public function OrderBy(string $column, bool $isAscending = true): static;
	public function Limit(int $limit): static;
	public function GroupBy(): static;
	public function Having(): static;
}