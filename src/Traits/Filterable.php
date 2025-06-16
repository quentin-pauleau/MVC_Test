<?php
namespace Traits;

trait Filterable
{
	/**
	 * Filter the value as an string
	 * 
	 * using filter_var with FILTER_DEFAULT
	 * @param string $name
	 * @param bool $quotes_allowed if false FILTER_FLAG_NO_ENCODE_QUOTES is added to the filter
	 * @return string|false|null
	 */
	public function FilterString(string $name, bool $quotes_allowed = false): ?string {
		if (!isset($this->value[$name])) {
			return null;
		}

		if ($quotes_allowed) {
			return filter_var($this->value[$name], FILTER_DEFAULT);
		}

		return filter_var($this->value[$name], FILTER_DEFAULT, FILTER_FLAG_NO_ENCODE_QUOTES);
	}


	/**
	 * Filter the value as an integer
	 * 
	 * using filter_var with FILTER_VALIDATE_INT
	 * @param string $name
	 * @return int|false|null
	 */
	public function FilterInt(string $name) {
		if (!isset($this->value[$name])) {
			return null;
		}

		return filter_var($this->value[$name], FILTER_VALIDATE_INT);
	}
	
	
	/**
	 * Filter the value as an float
	 * 
	 * using filter_var with FILTER_VALIDATE_FLOAT
	 * @param string $name
	 * @return float|false|null
	 */
	public function FilterFloat(string $name) {
		if (!isset($this->value[$name])) {
			return null;
		}

		return filter_var($this->value[$name], FILTER_VALIDATE_FLOAT);
	}


	/**
	 * Filter the value as an boolean
	 * 
	 * using filter_var with FILTER_VALIDATE_BOOLEAN
	 * @param string $name
	 * @return bool|false|null
	 */
	public function FilterBool(string $name) {
		if (!isset($this->value[$name])) {
			return null;
		}
		
		return filter_var($this->value[$name], FILTER_VALIDATE_BOOLEAN);
	}
}