<?php



/**
 * Filter the value as an string
 * 
 * using filter_var with FILTER_DEFAULT
 * @param string $value
 * @param bool $quotes_allowed if false FILTER_FLAG_NO_ENCODE_QUOTES is added to the filter
 * @return string|false
 */
function filter_string($value, bool $quotes_allowed = false): ?string {
	if ($quotes_allowed) {
		return filter_var($value, FILTER_DEFAULT);
	}

	return filter_var($value, FILTER_DEFAULT, FILTER_FLAG_NO_ENCODE_QUOTES);
}


/**
 * Filter the value as an integer
 * 
 * using filter_var with FILTER_VALIDATE_INT
 * @param string $value
 * @return int|false
 */
function filter_int($value) {
	return filter_var($value, FILTER_VALIDATE_INT);
}


/**
 * Filter the value as an float
 * 
 * using filter_var with FILTER_VALIDATE_FLOAT
 * @param string $value
 * @return float|false
 */
function filter_float($value) {
	return filter_var($value, FILTER_VALIDATE_FLOAT);
}


/**
 * Filter the value as an boolean
 * 
 * using filter_var with FILTER_VALIDATE_BOOLEAN
 * @param string $value
 * @return bool|false
 */
function filter_bool($value) {	
	return filter_var($value, FILTER_VALIDATE_BOOLEAN);
}

/**
 * Filter the value as an email
 * 
 * using filter_var with FILTER_VALIDATE_EMAIL
 * @param string $value
 * @return string|false
 */
function filter_email($value) {
	return filter_var($value, FILTER_VALIDATE_EMAIL);
}

/**
 * Filter the value as an url
 * 
 * using filter_var with FILTER_VALIDATE_URL
 * @param string $value
 * @return string|false
 */
function filter_url($value) {
	return filter_var($value, FILTER_VALIDATE_URL);
}

/**
 * Filter the value as an ip
 * 
 * using filter_var with FILTER_VALIDATE_IP
 * @param string $value
 * @return string|false
 */
function filter_ip($value) {
	return filter_var($value, FILTER_VALIDATE_IP);
}

/**
 * Filter the value as an mac address
 * 
 * using filter_var with FILTER_VALIDATE_MAC
 * @param string $value
 * @return string|false
 */
function filter_mac($value) {
	return filter_var($value, FILTER_VALIDATE_MAC);
}

/**
 * Filter the value as an class name
 * 
 * using class_exists to check if the class exists
 * @param string $value
 * @return string|false
 */
function filter_class($value) {
	if (!class_exists($value)) {
		return false;
	}

	return $value;
}

/**
 * Filter the value as an object of a given class
 * 
 * using is_a to check if the object is of the given class
 * @param object $value
 * @param string|null $class
 * @return object|false
 */
function filter_object($value, ?string $class = null) {
	if (!is_object($value)) {
		return false;
	}

	if ($class === null) {
		return $value;
	}

	if (!is_a($value, $class)) {
		return false;
	}

	return $value;
}