<?php
namespace Models\ExtendedNumerics;

use Modules\ExtendedInt\FloatNumber;
use Modules\ExtendedInt\IntNumber;
use NumberFormatter;

trait NumericOpperations
{
	
	public function Add(int|IntNumber|float|FloatNumber ...$value): static {
		foreach($value as $v)
			if ($v instanceof IntNumber)
				$this->value += $v->value;
			elseif ($v instanceof FloatNumber)
				$this->value += $v->value;
			else
				$this->value += $v;

		return $this;
	}

	public function Sub(int|IntNumber|float|FloatNumber ...$value): static {
		foreach($value as $v)
			if ($v instanceof IntNumber)
				$this->value -= $v->value;
			elseif ($v instanceof FloatNumber)
				$this->value -= $v->value;
			else
				$this->value -= $v;

		return $this;
	}

	public function Mult(int|IntNumber|float|FloatNumber ...$value): static {
		foreach($value as $v)
			if ($v instanceof IntNumber)
				$this->value *= $v->value;
			elseif ($v instanceof FloatNumber)
				$this->value *= $v->value;
			else
				$this->value *= $v;

		return $this;
	}

	public function SqPow(): static {
		$this->value **= 2;

		return $this;
	}

	public function Sqrt(): static {
		$this->value = sqrt($this->value);
		
		return $this;
	}

	public function Pow(int|IntNumber|float|FloatNumber $exponent): static {
		if ($exponent instanceof IntNumber)
			$this->value **= $exponent->value;
		elseif ($exponent instanceof FloatNumber)
			$this->value **= $exponent->value;
		else
			$this->value **= $exponent;

		return $this;
	}

	public function Div(int|IntNumber|float|FloatNumber ...$value): static {
		foreach($value as $v)
			if ($v instanceof IntNumber)
				$this->value /= $v->value;
			elseif ($v instanceof FloatNumber)
				$this->value /= $v->value;
			else
				$this->value /= $v;

		return $this;
	}

	public function Mod(int|IntNumber|float|FloatNumber ...$value): static {
		foreach($value as $v)
			if ($v instanceof IntNumber)
				$this->value %= $v->value;
			elseif ($v instanceof FloatNumber)
				$this->value %= $v->value;
			else
				$this->value %= $v;

		return $this;
	}


	
	public function isEq(int|IntNumber|float|FloatNumber ...$value): bool {
		foreach($value as $v)
			if ($v instanceof IntNumber || $v instanceof FloatNumber)
				if (!($this->value == $v->value))
					return false;
			else
				if (!($this->value == $v))
					return false;
		
		return true;
	}
	
	public function isEqStrict(int|IntNumber|float|FloatNumber ...$value): bool {
		foreach($value as $v)
			if ($v instanceof IntNumber || $v instanceof FloatNumber)
				if (!($this->value === $v->value))
					return false;
			else
				if (!($this->value === $v))
					return false;
		
		return true;
	}
	
	public function isDif(int|IntNumber|float|FloatNumber ...$value): bool {
		foreach($value as $v)
			if ($v instanceof IntNumber || $v instanceof FloatNumber)
				if (!($this->value != $v->value))
					return false;
			else
				if (!($this->value != $v))
					return false;
		
		return true;
	}
	
	public function isDifStrict(int|IntNumber|float|FloatNumber ...$value): bool {
		foreach($value as $v)
			if ($v instanceof IntNumber || $v instanceof FloatNumber)
				if (!($this->value !== $v->value))
					return false;
			else
				if (!($this->value !== $v))
					return false;
		
		return true;
	}
	
	public function isGt(int|IntNumber|float|FloatNumber ...$value): bool {
		foreach($value as $v)
			if ($v instanceof IntNumber || $v instanceof FloatNumber)
				if (!($this->value > $v->value))
					return true;
			else
				if (!($this->value > $v))
					return true;
		
		return true;
	}
	
	public function isGtEq(int|IntNumber|float|FloatNumber ...$value): bool {
		foreach($value as $v)
			if ($v instanceof IntNumber || $v instanceof FloatNumber)
				if (!($this->value >= $v->value))
					return true;
			else
				if (!($this->value >= $v))
					return true;
		
		return true;
	}
	
	public function isLt(int|IntNumber|float|FloatNumber ...$value): bool {
		foreach($value as $v)
			if ($v instanceof IntNumber || $v instanceof FloatNumber)
				if (!($this->value <= $v->value))
					return true;
			else
				if (!($this->value <= $v))
					return true;
		
		return true;
	}
	
	public function isLtEq(int|IntNumber|float|FloatNumber ...$value): bool {
		foreach($value as $v)
			if ($v instanceof IntNumber || $v instanceof FloatNumber)
				if (!($this->value <= $v->value))
					return true;
			else
				if (!($this->value <= $v))
					return true;
		
		return true;
	}
}