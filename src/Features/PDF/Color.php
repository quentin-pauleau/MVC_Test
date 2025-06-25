<?php
namespace Feature\PDF;


class Color
{
	public int $r;
	public int $g;
	public int $b;


	public function __construct(
		int $r = 0,
		int $g = 0,
		int $b = 0
	) {
		$this->r = $r;
		$this->g = $g;
		$this->b = $b;
	}


	public static function FromRGB(
		int $r,
		int $g,
		int $b
	): self {
		return new self($r, $g, $b);
	}
}