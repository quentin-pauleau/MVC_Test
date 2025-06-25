<?php
namespace Feature\PDF\Enums;

use Exception;
use Traits\StaticClass;

class PDFUnits
{
	use StaticClass;

	public const DEFAULT = self::MILLIMETERS;
	public const POINT = 0;
	public const MILLIMETERS = 1;
	public const CENTIMETERS = 2;
	public const INCHES = 3;


	public static function GetType(int $PDFOrientation): string {
		switch ($PDFOrientation) {
			case self::POINT:
				return 'pt';
			
			case self::MILLIMETERS:
				return 'mm';
			
			case self::CENTIMETERS:
				return 'cm';
			
			case self::INCHES:
				return 'in';
		}

		throw new Exception();
	}
}