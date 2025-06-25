<?php
namespace Feature\PDF;

/**
 * Point on a 2D plain, here the PDF
 */
final class Vector2
{
	public float $x = 0;
	public float $y = 0;


	public function __construct(
		float $x = 0, 
		float $y = 0
	) {
		$this->x = $x; 
		$this->y = $y;
	}

	public static function ZERO(): self {
		return new self;
	}

	public static function LEFT(): self {
		return new self(-1, 0);
	}

	public static function RIGHT(): self {
		return new self(1, 0);
	}

	public static function UP(): self {
		return new self(0, 1);
	}

	public static function DOWN(): self {
		return new self(0, -1);
	}

	public static function Clamp(self $value, self $min, self $max): self {
		$new = clone $value;

		//* clamp y
		if ($new->x > $max->x)
			$new->x = $max->x;
		
		if ($new->x < $min->x)
			$new->x = $min->x;

		//* clamp y
		if ($new->y > $max->y)
			$new->y = $max->y;
		
		if ($new->y < $min->y)
			$new->y = $min->y;
		
		return $new;
	}


	public static function Add(self ...$Points): self {
		$new = new Vector2;

		foreach ($Points as $Point) {
			$new->x += $Point->x;
			$new->y += $Point->y;
		}

		return $new;
	}


	public static function Sub(self ...$Points): self {
		if ($Points === [])
			return new Vector2;

		$new = clone $Points[0];

		foreach (array_diff($Points, $Points[0]) as $Point) {
			$new->x -= $Point->x;
			$new->y -= $Point->y;
		}

		return $new;
	}
}