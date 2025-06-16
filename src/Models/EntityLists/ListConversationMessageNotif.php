<?php
namespace Models\EntityLists;

use Models\EntityLists\ListEntity;
use Models\Entities\ConversationMessageNotif;

class ListConversationMessageNotif extends ListEntity
{
	/**
	 * @var array<int, ConversationMessageNotif> $values
	 */
	public array $values = [];

	
	public function __construct(ConversationMessageNotif ...$values) {
		foreach ($values as $value) {
			$this->values[] = $value;
		}
	}


	/**
	 * Add values to the list
	 * @param ConversationMessageNotif[] $values
	 * @return void
	 */
	public function Add(ConversationMessageNotif ...$values): void {
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
	 * @param ConversationMessageNotif[][] $values
	 * @return void
	 */
	public function AddArray(array ...$arrays): void {
		foreach ($arrays as $array) {
			if (!is_array_of_class($array, ConversationMessageNotif::class)) {
				continue;
			}
			$this->values = array_merge($this->values, $array);
		}
	}


	/**
	 * Remove values from the list
	 * @param ConversationMessageNotif[] $values
	 * @return void
	 */
	public function Remove(ConversationMessageNotif ...$values): void {
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
	 * @param ConversationMessageNotif[][] $arrays
	 * @return void
	 */
	public function RemoveArray(array ...$arrays): void {
		parent::RemoveArray($arrays);
	}


	/**
	 * Return a value at a given index
	 * @param int $index
	 * @return ConversationMessageNotif
	 */
	public function Get(int $index): ConversationMessageNotif {
		return $this->values[$index];
	}

	
	/**
	 * Return the current message notification
	 * @return ConversationMessageNotif
	 */
	public function Current(): ConversationMessageNotif {
		return $this->values[$this->current_key];
	}
}