<?php
namespace Modules\ExtendedInt;

use Models\ExtendedNumerics\NumericOpperations;
use Stringable;

class IntNumber implements Stringable
{
	use NumericOpperations;

	public int $value;


	public function __construct(int $value) {
		$this->value = $value;
	}

	public static function fromInt(int $value): self {
		return new self($value);
	}

	public static function fromString(string $value): self {
		return new self($value);
	}

	

	public function __tostring() : string {
		return (string) $this->value;
	}
}