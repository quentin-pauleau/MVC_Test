<?php
namespace Modules\Eclipse;


final class Settings
{
	public static self|null $instance = null;
	private array $settings;


	public static function GetInstance(): self
	{
		return self::$instance ??= new self();
	}

	public static function GetSettings(): self
	{
		return self::GetInstance();
	}

	public function __construct()
	{

	}

	public function Get(string $key): mixed
	{
		return $this->settings[$key];
	}
}