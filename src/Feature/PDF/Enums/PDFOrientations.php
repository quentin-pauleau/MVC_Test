<?php
namespace Feature\PDF\Enums;

use Exception;
use Traits\StaticClass;

final class PDFOrientations
{
	use StaticClass;

	public const DEFAULT = self::PORTRAIT;
	public const PORTRAIT = 0;
	public const LANDSCAPE = 1;


	public static function GetType(int $PDFOrientation): string {
		switch ($PDFOrientation) {
			case self::PORTRAIT:
				return 'P';
			
			case self::LANDSCAPE:
				return 'L';
		}

		throw new Exception();
	}
}