<?php
namespace Modules\HTMLElement\Managers;

use Modules\HTMLElement\HTMLElement;


/**
 * Manager that assure tabindex uniqueness
 * 
 * used by :
 * - Dropdown
 */
class IdManager
{
	private static ?IdManager $instance = null;

	public static function getManager(): IdManager {
		return self::$instance ?? new self;
	}

	private function __construct() {}


	/**
	 * Array to hold registered IDs
	 * @var array<HTMLElement,string>
	 */
	private array $ids = [];


	/**
	 * Summary of registerNewElement
	 * @param \Modules\HTMLElement\HTMLElement $element
	 * @param ?string $id the element will be registered with this ID, if null a random ID will be generated
	 * @throws \InvalidArgumentException
	 * @return void
	 */
	public function registerNewElement(HTMLElement $element, ?string $id = null): void
	{
		if ($this->findElement($element))
			throw new \InvalidArgumentException("Element is already registered with id '{$this->findId($element)}', it can not be registered with new id '$id'");

		$id ??= $this->generateRandomId();

		if ($this->findElement($id))
			throw new \InvalidArgumentException("An element is already registered with id '$id', it can not be used by another element.");

		$this->ids[$id] = $element;
	}


	private function generateRandomId(): string
	{
		return uniqid('element_', true);
	}


	public function getElementIndex(string $id): ?int
	{
		return $this->ids[$id] ?? null;
	}


	public function findElement(string $id): ?HTMLElement
	{
		return $this->ids[$id] ?? null;
	}

	public function findId(HTMLElement $element): ?string
	{
		return array_search($element, $this->ids) ?: null;
	}
}
