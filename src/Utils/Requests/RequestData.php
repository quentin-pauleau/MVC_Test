<?php
namespace Utils\Requests;

use Traits\Filterable;

/**
 * @template-contravariant string
 * @template-covariant mixed|iterable
 */
class RequestData extends RequestDataAbstract
{
	public function __construct(array $source) {
		$this->AddArray($source);
	}


	public function Get(string $name): ?string {
		return parent::Get($name);
	}
	
	/**
	 * Summary of current
	 * @return mixed
	 */
	public function current(): string {
		return $this->values[$this->current_name];
	}

	
	/**
	 * Filter the value as an string
	 * 
	 * using filter_var with FILTER_DEFAULT
	 * @param string $name
	 * @param bool $quotes_allowed if false FILTER_FLAG_NO_ENCODE_QUOTES is added to the filter
	 * @return string|false|null
	 */
	public function FilterString(string $name, bool $use_htmlspecialchars = true, bool $quotes_allowed = false): ?string {
		if (!isset($this->values[$name])) {
			return null;
		}

		$var = filter_string($this->values[$name], $quotes_allowed);

		if ($use_htmlspecialchars && $var != false) {
			return htmlspecialchars($var);
		}

		return $var;
	}


	/**
	 * Filter the value as an integer
	 * 
	 * using filter_var with FILTER_VALIDATE_INT
	 * @param string $name
	 * @return int|false|null
	 */
	public function FilterInt(string $name) {
		if (!isset($this->values[$name])) {
			return null;
		}

		return filter_int($this->values[$name]);
	}
	
	
	/**
	 * Filter the value as an float
	 * 
	 * using filter_var with FILTER_VALIDATE_FLOAT
	 * @param string $name
	 * @return float|false|null
	 */
	public function FilterFloat(string $name) {
		if (!isset($this->values[$name])) {
			return null;
		}

		return filter_float($this->values[$name]);
	}


	/**
	 * Filter the value as an boolean
	 * 
	 * using filter_var with FILTER_VALIDATE_BOOLEAN
	 * @param string $name
	 * @return bool|false|null
	 */
	public function FilterBool(string $name) {
		if (!isset($this->values[$name])) {
			return null;
		}
		
		return filter_bool($this->values[$name]);
	}
}