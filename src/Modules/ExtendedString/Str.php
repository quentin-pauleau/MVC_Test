<?php
namespace Modules\ExtendedString;

use Countable;
use Stringable;

/**
 * alias for new Str()
 * @param string $str
 * @return Str
 */
function str(string $str): Str {
	return new Str($str);
}

class Str implements Stringable, Countable 
{
	public const EMPTY = '';
	private string $str;

	public function __construct(string $str) {
		$this->str = $str;
	}

	public static function fromString(string $str): self {
		return new self($str);
	}

	final public function count(): int {
		return strlen($this->str);
	}

	public function __toString(): string {
		return $this->str;
	}


	public function get(): string {
		return $this->str;
	}

	public function set(string $str): void {
		$this->str = $str;
	}


	public function concat(string ...$str): static {
		$this->str .= implode('', $str);
		return $this;
	}


	public function concatAtStart(string ...$str): static {
		$this->str = implode('', $str).$this->str;
		return $this;
	}


	#region boolean functions
	public function isEmpty(): bool {
		return $this->str == '';
	}

	public function isWhitespace(): bool {
		return ctype_space($this->str);
	}
	
	public function isSpace(): bool {
		return ctype_space($this->str);
	}
	
	public function isAlpha(): bool {
		return ctype_alpha($this->str);
	}

	public function isNumeric(): bool {
		return ctype_digit($this->str);
	}

	public function isAlphanumeric(): bool {
		return ctype_alnum($this->str);
	}

	public function isLowercase(): bool {
		return ctype_lower($this->str);
	}

	public function isUppercase(): bool {
		return ctype_upper($this->str);
	}

	public function isPrintable(): bool {
		return ctype_print($this->str);
	}

	public function isGraphical(): bool {
		return ctype_graph($this->str);
	}

	public function isPunctuation(): bool {
		return ctype_punct($this->str);
	}

	public function isControlCharacter(): bool {
		return ctype_cntrl($this->str);
	}

	public function isHexadecimal(): bool {
		return ctype_xdigit($this->str);
	}


	public function isContaining(string $needle): bool {
		return str_contains($this->str, $needle);
	}

	public function isStartingWith(string $prefix): bool {
		return str_starts_with($this->str, $prefix);
	}

	public function isEndingWith(string $suffix): bool {
		return str_ends_with($this->str, $suffix);
	}


	public function isPalindrome(): bool {
		return strtolower(strrev($this->str)) === strtolower($this->str);
	}

	public function isAnagramOf(string $other): bool {
		if(strlen($this->str) !== strlen($other))
			return false;

		$chars1 = array_count_values(str_split(strtolower($this->str)));

		$chars2 = array_count_values(str_split(strtolower($other)));
		
		foreach ($chars1 as $char => $count)
			if(!isset($chars2[$char]) || $chars2[$char] != $count)
				return false;
		
		return true;
	}


	public function isVerifiedBy(string $pattern): bool {
		if (!str_starts_with($pattern, '/'))
			$pattern = "/$pattern";

		if (!str_ends_with($pattern, '/'))
			$pattern .= '/';
		
		return preg_match($pattern, $this->str) != false;
	}


	#endregion boolean function


	#region modification functions



	public function ltrim(): static {
		$this->str = ltrim($this->str);
		return $this;
	}

	public function rtrim(): static {
		$this->str = rtrim($this->str);
		return $this;
	}

	

	public function reverse(): static {
		$this->str = strrev($this->str);
		return $this;
	}

	public function shuffle(): static {
		$this->str = str_shuffle($this->str);
		return $this;
	}

	public function replace(string $search, string $replace): static {
		$this->str = str_replace($search, $replace, $this->str);
		return $this;
	}

	public function remove(string $search): static {
		$this->str = str_replace($search, '', $this->str);
		return $this;
	}




	#endregion modification functions


	#region conversion functions

	public function toLower(): static {
		$this->str = strtolower($this->str);
		return $this;
	}

	public function toUpper(): static {
		$this->str = strtoupper($this->str);
		return $this;
	}

	public function capitalize(): static {
		$this->str = ucfirst($this->str);
		return $this;
	}

	public function titleCase(): static {
		$this->str = ucwords($this->str);
		return $this;
	}



	#endregion conversion functions


	#region search functions


	public function index(int $index): string {
		if($index < 0)
			$index = strlen($this->str) + $index;

		if($index >= strlen($this->str) || $index < 0)
			throw new \Exception("Index out of bounds}");

		return $this->str[$index];
	}


	/**
	 * Get the first character in the string.
	 * An empty string will be returned if the string is empty.
	 * @return string
	 */
	public function first(): string {
		if(strlen($this->str) == 0)
			return '';

		return $this->str[0];
	}


	/**
	 * Get the last character in the string.
	 * An empty string will be returned if the string is empty.
	 * @return string
	 */
	public function last(): string {
		return substr($this->str, -1);
	}


	/**
	 * Find the first occurance of a string.
	 * If not found returns null.
	 * @param string $needle
	 * @return int|null
	 */
	public function findFirst(string $needle): ?int {
		return strpos($this->str, $needle) ?: null;
	}


	/**
	 * Find the last occurance of a string.
	 * If not found returns null.
	 * @param string $needle
	 * @return int|null
	 */
	public function findLast(string $needle): ?int {
		return strrpos($this->str, $needle) ?: null;
	}


	public function findAll(string ...$needle): array {
		$matches = [];
		preg_match_all("/$needle/i", $this->str, $matches);
		return $matches[0];
	}


	public function findAny(string ...$needles): ?array {
		foreach ($needles as $needle) {
			$result = $this->findFirst($needle);
			if($result !== null)
				return [$needle, $result];
		}
		return null;
	}


	public function findRegex(string $regex): array {
		$match = [];
		preg_match_all("/$regex/", $this->str, $match);
		return $match;
	}

	#endregion search functions
}