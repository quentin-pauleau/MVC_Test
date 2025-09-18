<?php
namespace Modules\Collection;


class AbstractCollection implements \Countable, \IteratorAggregate
{
	private array $items = [];

	public function __construct(array $items = []) {
		$this->items = $items;
	}

	public function count(): int {
		return count($this->items);
	}

	public function getIterator(): \Traversable {
		return new \ArrayIterator($this->items);
	}
}