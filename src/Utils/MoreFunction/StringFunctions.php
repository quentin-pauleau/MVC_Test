<?php


/**
 * Fonction officielle dispo en php 8.0 sous le nom "str_contains"
 * @param string $haystack
 * @param string $needle
 * @return bool
 */
function str_contains2(string $haystack, string $needle): bool {
	if ($needle === "") {
		return true;
	}

	return strpos($haystack, $needle);
}

/**
 * Fonction officielle dispo en php 8.0 sous le nom "str_starts_with"
 * @param string $haystack
 * @param string $needle
 * @return bool
 */
function str_starts_with2(string $haystack, string $needle): bool {
	if ($needle === "") {
		return true;
	}

	$pos = strpos($haystack, $needle);

	if ($pos === 0) {
		return true;
	}

	return false;
}


/**
 * Fonction officielle dispo en php 8.0 sous le nom "str_ends_with"
 * @param string $haystack
 * @param string $needle
 * @return bool
 */
function str_ends_with2(string $haystack, string $needle): bool {
	return strlen($needle) === 0 || substr($haystack, -strlen($needle)) === $needle;
}