<?php
/**
 * @package Utils\MoreFunctions\ArrayFunctions
 * 
 * This file contains functions that are used to manipulate arrays.
 * 
 * It contains functions to:
 * - Duplicate an array
 * - Check if all values in an array are of a specific type
 * - Check if all values in an array are of a specific class
 */


/**
 * Return a duplicate version of the given array
 * @param array $array
 * @return array
 */
function array_duplicate(array $array, bool $clone_objects = false): array {
	$data = [];

	if ($clone_objects) {
		foreach($array as $k => $v) {
			if (is_object($v)) {
				$data[$k] = clone $v;
				continue;
			}

			$data[$k] = $v;
		}

		return $data;
	}

	foreach($array as $k => $v)
		$data[$k] = $v;

	return $data;
}

/**
 * Return a recusively duplicate version of the given array
 * @param array $array
 * @return void
 */
function array_duplicate_recursive(array $array, bool $clone_objects = false): array {
	$data = [];

	if ($clone_objects) {
		foreach($array as $k => $v) {
			if (is_object($v)) {
				$data[$k] = clone $v;
				continue;
			}

			if (is_array($v)) {
				$data[$k] = array_duplicate_recursive($v, $clone_objects);
				continue;
			}

			$data[$k] = $v;
		}

		return $data;
	}

	foreach($array as $k => $v) {
		if (is_array($v)) {
			$data[$k] = array_duplicate_recursive($v, $clone_objects);
			continue;
		}

		$data[$k] = $v;
	}

	return $data;
}


/**
 * Return true if all values in the array are instance of the class
 * @param array $array
 * @param string $type
 * @return bool
 */
function is_array_of_class(string $class, array ...$array): bool {
	foreach ($array as $a) {
		foreach ($a as $value) {
			if (is_a($value, $class)) {
				return false;
			}
		}
	}
	return true;
}

function is_array_of_int(array ...$array): bool {
	foreach ($array as $a) {
		foreach ($a as $value) {
			if (!is_int($value)) {
				return false;
			}
		}
	}
	return true;
}

function is_array_of_float(array ...$array): bool {
	foreach ($array as $a) {
		foreach ($a as $value) {
			if (!is_float($value)) {
				return false;
			}
		}
	}
	return true;
}

function is_array_of_bool(array ...$array): bool {
	foreach ($array as $a) {
		foreach ($a as $value) {
			if (!is_bool($value)) {
				return false;
			}
		}
	}
	return true;
}

function is_array_of_string(array ...$array): bool {
	foreach ($array as $a) {
		foreach ($a as $value) {
			if (!is_string($value)) {
				return false;
			}
		}
	}
	return true;
}