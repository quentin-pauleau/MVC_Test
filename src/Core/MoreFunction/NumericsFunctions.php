<?php


function clampi(int $value, int $min, int $max): int {
	return max(min($value, $max), $min);
}


function clampf(float $value, float $min, float $max): float {
	return max(min($value, $max), $min);
}