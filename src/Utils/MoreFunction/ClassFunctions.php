<?php

function class_uses_recursive($class, $autoload = true) {

	$traits = [];

	do {
		foreach (class_uses($class, $autoload) as $trait) {
			$traits[] = $trait;
		}
	} while($class = get_parent_class($class));
	
	return array_unique($traits);
}


function class_implements_recursive($class, $autoload = true) {
	$interfaces = [];
	
	do {
		$interfaces = array_merge(class_implements_recursive($class, $autoload), $interfaces);
	} while($class = get_parent_class($class));
	
	foreach ($interfaces as $interface => $same) {
		$interfaces = array_merge(class_uses($interface, $autoload), $interfaces);
	}
	
	return array_unique($interfaces);
}


/**
 * Check if a class implements all given traits
 * @param mixed $class
 * @param string[] $traits the list of traits to implements
 * @return bool
 */
function is_class_using($class, string ...$traits): bool {
	$result = array_intersect(class_uses_recursive($class), $traits);

	foreach ($traits as $trait) {
		if (!in_array($trait, $result)) {
			return false;
		}
	}

	return true;
}


/**
 * Check if a class implements all given interfaces
 * @param mixed $class
 * @param string[] $interfaces the list of interfaces to implements
 * @return bool
 */
function is_class_implementing($class, string ...$interfaces): bool {
	return array_intersect(
		class_uses_recursive($class), 
		$interfaces
	) === $interfaces;
}