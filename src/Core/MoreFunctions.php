<?php

require_once __DIR__."/../Core/MoreFunction/FilterFunctions.php";
require_once __DIR__."/../Core/MoreFunction/StringFunctions.php";
require_once __DIR__."/../Core/MoreFunction/ArrayFunctions.php";
require_once __DIR__."/../Core/MoreFunction/ClassFunctions.php";
require_once __DIR__."/../Core/MoreFunction/NumericsFunctions.php";

/**
 * dump and die
 * @param mixed $data
 * @return never
 */
function dd(...$data) {
	if (count($data) > 1) {
		foreach ($data as $d) {
			echo "<pre>";
			print_r($d);
			echo "</pre>";
			echo "<br> <hr> <br>";
		}
	}else {
		echo "<pre>";
		print_r($data[0]);
		echo "</pre>";
	}
	die();
}

/**
 * convert a iso string to an utf8
 * @param string $str
 * @return bool
 */
function Convert_encoding_to_utf8(string $str): string {
	if (!mb_check_encoding($str, "ISO-8859-1")) {
		return $str;
	}
	
	return mb_convert_encoding($str, "UTF-8", "ISO-8859-1");
}

/**
 * Convert an utf8 string to an iso
 * @param string $str
 * @return bool
 */
function Convert_encoding_to_iso(string $str): string {
	if (!mb_check_encoding($str, "UTF-8")) {
		return $str;
	}

	return mb_convert_encoding($str, "ISO-8859-1", "UTF-8");
}
