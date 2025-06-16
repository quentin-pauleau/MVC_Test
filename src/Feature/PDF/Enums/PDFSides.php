<?php
namespace Feature\PDF\Enums;

use Exception;
use Traits\StaticClass;

class PDFSides
{
	use StaticClass;

	public const NONE = 0;
	
	public const LEFT = 1;
	public const RIGHT = 2;
	public const TOP = 3;
	public const BOTTOM = 4;

	public const HORIZONTAL = 11;
	public const VERTICAL = 12;

	public const TOP_LEFT = 13;
	public const TOP_RIGHT = 14;
	public const BOTTOM_LEFT = 15;
	public const BOTTOM_RIGHT = 16;

	public const ALL = 7;


	public static function GetType(int $PDFOrientation): string {
		switch ($PDFOrientation) {
			//
			case self::NONE:
				return '';
			
			// 
			case self::LEFT:
				return 'L';
			
			case self::RIGHT:
				return 'R';
			
			case self::TOP:
				return 'T';
			
			case self::BOTTOM:
				return 'B';
			
			// 
			case self::HORIZONTAL:
				return 'LR';
			
			case self::VERTICAL:
				return 'TB';
			
			// 
			case self::TOP_LEFT:
				return 'TL';
			
			case self::TOP_RIGHT:
				return 'TR';
			
			case self::BOTTOM_LEFT:
				return 'BL';
			
			case self::BOTTOM_RIGHT:
				return 'BR';
			
			// 
			case self::ALL:
				return 'LRTB';
		}

		throw new Exception();
	}
}