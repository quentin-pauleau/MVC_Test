<?php
namespace Core\Requests;


/**
 * (experimental)
 */
class RequestCookies extends RequestDataAbstract
{
	/**
	 * @var array<string, mixed>
	 */
	protected array $data = [];

	
	public function __construct() {
		$this->data = $_COOKIE;
	}

	public function Get(string $name) {
		return $this->data[$name] ?? null;
	}
	
	/**
	 * Summary of current
	 * @return mixed
	 */
	public function current(): string {
		return $this->values[$this->current_name];
	}
}