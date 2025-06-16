<?php
namespace Feature\PDF\Enums;

use Exception;
use Traits\StaticClass;

class PDFSizes
{
	use StaticClass;

	public const DEFAULT = self::A4;
	public const A3 = 0;
	public const A4 = 1;
	public const A5 = 2;
	public const LETTER = 3;
	public const LEGAL = 4;


	public static function GetType(int $PDFOrientation): string {
		switch ($PDFOrientation) {
			case self::A3:
				return 'A3';
			
			case self::A4:
				return 'A4';
			
			case self::A5:
				return 'A5';
			
			case self::LETTER:
				return 'Letter';
			
			case self::LEGAL:
				return 'Legal';
		}

		throw new Exception();
	}
}