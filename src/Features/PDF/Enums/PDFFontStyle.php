<?php
namespace Feature\PDF\Enums;

use Exception;
use Traits\StaticClass;

class PDFFontStyle
{
	use StaticClass;

	public const DEFAULT = self::STANDARD;
	public const STANDARD = 0;
	public const BOLD = 1;
	public const ITALIC = 2;
	public const UNDERLINED = 3;


	public static function GetType(int $fontStyle): string {
		switch ($fontStyle) {
			case self::STANDARD:
				return '';
			
			case self::BOLD:
				return 'B';
			
			case self::ITALIC:
				return 'I';
			
			case self::UNDERLINED:
				return 'U';
		}

		throw new Exception();
	}
}