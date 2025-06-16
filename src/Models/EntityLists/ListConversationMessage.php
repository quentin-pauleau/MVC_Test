<?php
namespace Models\EntityLists;

use Models\EntityLists\ListEntity;
use Models\Entities\ConversationMessage;

class ListConversationMessage extends ListEntity
{
	/**
	 * @var array<int, ConversationMessage> $values
	 */
	public array $values = [];

	
	public function __construct(ConversationMessage ...$values) {
		foreach ($values as $value) {
			$this->values[] = $value;
		}
	}


	/**
	 * Add values to the list
	 * @param ConversationMessage[] $values
	 * @return void
	 */
	public function Add(ConversationMessage ...$values): void {
		foreach ($values as $value) {
			$this->values[] = $value;
		}
	}


	/**
	 * Add all values in the given list to the list
	 * @param self[] $lists
	 * @return void
	 */
	public function AddList(self ...$lists): void {
		foreach ($lists as $list) {
			$this->values = array_merge($this->values, $list->GetAll());
		}
	}


	/**
	 * Add all values in the given array to the list
	 * @param ConversationMessage[][] $values
	 * @return void
	 */
	public function AddArray(array ...$arrays): void {
		foreach ($arrays as $array) {
			if (!is_array_of_class($array, ConversationMessage::class)) {
				continue;
			}
			$this->values = array_merge($this->values, $array);
		}
	}


	/**
	 * Remove values from the list
	 * @param ConversationMessage[] $values
	 * @return void
	 */
	public function Remove(ConversationMessage ...$values): void {
		foreach ($values as $value) {
			$key = array_search($value, $this->values);

			if ($key === false) {
				continue;
			}

			array_splice($this->values, $key, 1);
		}
	}


	/**
	 * Remove all values in the given lists from the list
	 * @param self[] $values
	 * @return void
	 */
	public function RemoveList(self ...$lists): void {
		foreach ($lists as $list) {
			foreach ($list->GetAll() as $value) {
				$key = array_search($value, $this->values);

				if ($key === false) {
					continue;
				}
	
				array_splice($this->values, $key, 1);
			}
		}
	}


	/**
	 * Remove values in the given arrays from the list
	 * @param ConversationMessage[][] $arrays
	 * @return void
	 */
	public function RemoveArray(array ...$arrays): void {
		parent::RemoveArray($arrays);
	}


	/**
	 * Return a value at a given index
	 * @param int $index
	 * @return ConversationMessage
	 */
	public function Get(int $index): ConversationMessage {
		return $this->values[$index];
	}

	
	/**
	 * Return the current cde client h
	 * @return ConversationMessage
	 */
	public function Current(): ConversationMessage {
		return $this->values[$this->current_key];
	}
}