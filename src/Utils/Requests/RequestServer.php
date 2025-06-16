<?php
namespace Utils\Requests;

use Traits\Singleton;


class RequestServer extends RequestDataAbstract
{
	use Singleton;


	public function __construct() {
		$this->AddArray($_SERVER);
	}

	public function Get(string $name): ?string {
		return parent::Get($name);
	}

	public function GetMethod(): string {
		return $this->Get('REQUEST_METHOD');
	}

	public function GetUri(): string {
		return $this->Get('REQUEST_URI');
	}

	public function GetHost(): string {
		return $this->Get('HTTP_HOST');
	}

	public function GetReferer(): string {
		return $this->Get('HTTP_REFERER');
	}
	
	/**
	 * Summary of current
	 * @return mixed
	 */
	public function current(): string {
		return $this->values[$this->current_name];
	}
}