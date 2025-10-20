<?php
namespace Models\ExtendedNumerics;


abstract class Math
{
	/**
	 * Approximation of the number π (pi). (3.14159265358979323846).
	 * @link http://php.net/manual/en/math.constants.php#constant.pi
	 */
	public const PI = M_PI;

	/**
	 * Approximation of Euler's number e (2.7182818284590452354).
	 * @link http://php.net/manual/en/math.constants.php#constant.e
	 */
	public const E = M_E;

	/**
	 * Approximation of log2(e) (1.4426950408889634074).
	 * @link http://php.net/manual/en/math.constants.php#constant.log2e
	 */
	public const LOG2E = M_LOG2E;
	
	/**
	 * Approximation of log10(e) (0.43429448190325182765).
	 * @link http://php.net/manual/en/math.constants.php#constant.log10e
	 */
	public const LOG10E = M_LOG10E;

	/**
	 * Approximation of ln(2) (0.69314718055994530942).
	 * @link http://php.net/manual/en/math.constants.php#constant.ln2
	 */
	public const LN2 = M_LN2;
	
	/**
	 * Approximation of ln(10) (2.30258509299404568402).
	 * @link http://php.net/manual/en/math.constants.php#constant.ln10
	 */
	public const LN10 = M_LN10;
	
	/**
	 * Approximation of π/2 (1.57079632679489661923).
	 * @link https://www.php.net/manual/math.constants#constant.pi-2
	 */
	public const PI_2 = M_PI_2;

	/**
	 * Approximation of π/4 (0.78539816339744830962).
	 * @link https://www.php.net/manual/math.constants#constant.pi-4
	 */
	public const PI_4 = M_PI_4;

	/**
	 * Approximation of 1/π (0.31830988618379067154).
	 * @link https://www.php.net/manual/math.constants#constant.m-1-pi
	 */
	public const _1_PI = M_1_PI;

	/**
	 * Approximation of 2/π (0.63661977236758134308).
	 * @link https://www.php.net/manual/math.constants#constant.m-2-pi
	 */
	public const _2_PI = M_2_PI;

	/**
	 * Approximation of sqrt(π) (1.12837916709551257390).
	 * @link https://www.php.net/manual/math.constants#constant.m-sqrtpi
	 */
	public const SQRTPI = M_SQRTPI;

	/**
	 * Approximation of 2/sqrt(π) (1.12837916709551257390).
	 * @link https://www.php.net/manual/math.constants#constant.m-2-sqrtpi
	 */
	public const _2_SQRTPI = M_2_SQRTPI;

	/**
	 * Approximation of sqrt(2) (1.41421356237309504880).
	 * @link https://www.php.net/manual/math.constants#constant.m-sqrt2
	 */
	public const SQRT2 = M_SQRT2;

	/**
	 * Approximation of sqrt(3) (1.73205080756887729352).
	 * @link https://www.php.net/manual/math.constants#constant.m-sqrt3
	 */
	public const SQRT3 = M_SQRT3;

	/**
	 * Approximation of sqrt(1/2) (0.70710678118654752440).
	 * @link https://www.php.net/manual/math.constants#constant.m-sqrt1-2
	 */
	public const SQRT1_2 = M_SQRT1_2;

	/**
	 * Approximation of ln(π) (1.14472988584940017414).
	 * @link https://www.php.net/manual/math.constants#constant.m-lnpi
	 */
	public const LNPI = M_LNPI;

	/**
	 * Approximation of Euler's constant γ (0.57721566490153286061).
	 * @link https://www.php.net/manual/math.constants#constant.m-euler
	 */
	public const EULER = M_EULER;

	/**
	 * Not A Number.
	 * @link https://www.php.net/manual/en/math.constants.php#constant.nan
	 */
	public const NAN = NAN;
	// Not A Number

	/**
	 * The infinite
	 * @link https://www.php.net/manual/en/math.constants.php#constant.inf
	 */
	public const INF = INF;

	public static function Factorial(int $n): int
	{
		if ($n == 0)
			return 1;
		
		return $n * self::Factorial($n - 1);
	}

	public static function Fibonacci(int $n): int
	{
		if ($n <= 2)
			return 1;

		return self::Fibonacci($n - 1) + self::Fibonacci($n - 2);
	}

	public static function PythagoreanTheorem(float $a, float $b): float
	{
		return sqrt($a ** 2 + $b ** 2);
	}

	public static function Distance(float $x1, float $y1, float $x2, float $y2): float
	{
		return sqrt(($x2 - $x1) ** 2 + ($y2 - $y1) ** 2);
	}



	/**
	 * Return true if the given number is ap prime number
	 * @param int|\Modules\ExtendedInt\IntNumber $n
	 * @return bool
	 */
	public static function IsPrime(int $n): bool
	{
		for ($i = 2; $i < sqrt($n); ++$i)
			if (($n % $i) === 0)
				return false;
		
		return true;
	}


	/**
	 * Find the Greatest Common Diviseur
	 * @param int|\Modules\ExtendedInt\IntNumber $a
	 * @param int|\Modules\ExtendedInt\IntNumber $b
	 * @return int
	 */
	public static function GCD(int $a, int $b): int
	{
		while ($a != $b)
			if ($a > $b)
				$a -= $b;
			else
				$b -= $a;
		
		return $a;
	}

	/**
	 * Find the Least Common Diviseur
	 * @param int|\Modules\ExtendedInt\IntNumber $a
	 * @param int|\Modules\ExtendedInt\IntNumber $b
	 * @return int
	 */
	public static function LCD(int $a, int $b): int
	{
		return (int)(abs($a * $b)) / self::GCD($a, $b);
	}


	public static function Power(int $base, int $exponent): int
	{
		return $base ** $exponent;
	}


	public static function SquareRoot(int $number): float
	{
		return sqrt($number);
	}

	public static function Root(int $number, int $root): float
	{
		return pow($number, 1/$root);
	}

	public static function Logarithm(int $number, int $base): float
	{
		return log($number, $base);
	}

	public static function Exponential(int $number): float
	{
		return exp($number);
	}

	public static function Sin(float $angle): float
	{
		return sin($angle);
	}

	public static function Cos(float $angle): float
	{
		return cos($angle);
	}

	public static function Tan(float $angle): float
	{
		return tan($angle);
	}

	public static function ArcSin(float $angle): float
	{
		return asin($angle);
	}

	public static function ArcCos(float $angle): float
	{
		return acos($angle);
	}

	public static function ArcTan(float $angle): float
	{
		return atan($angle);
	}

	public static function ArcTan2(float $y, float $x): float
	{
		return atan2($y, $x);
	}

	
	public static function Random(int $min, int $max): int
	{
		return rand($min, $max);
	}
	

	public static function RandomFloat(float $min, float $max): float
	{
		return mt_rand($min, $max);
	}

	public static function Lerp(int $start, int $end, float $delta): int
	{
		return round($start + ((float)($end - $start)) * $delta);
	}

	public static function Lerpf(float $start, float $end, float $delta): float
	{
		return round($start + ($end - $start) * $delta);
	}

	public static function InverseLerp(int $start, int $end, int $value): float
	{
		return (float)($value - $start) / ($end - $start);
	}

	public static function Clamp(int $value, int $min, int $max): int
	{
		return max(min($value, $max), $min);
	}

	public static function Clampf(float $value, float $min, float $max): float
	{
		return max(min($value, $max), $min);
	}

	public static function Wrap(int $value, int $min, int $max): int
	{
		return min(max($value, $min), $max);
	}

	public static function Wrapf(float $value, float $min, float $max): float
	{
		return min(max($value, $min), $max);
	}

	public static function Absolute(int $value): int
	{
		return abs($value);
	}

	public static function Absolutef(float $value): float
	{
		return abs($value);
	}


	public static function Sign(float $value): int
	{
		return $value >= 0 ? 1 : -1;
	}


}