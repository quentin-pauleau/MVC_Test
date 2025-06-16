<?php
namespace Traits;

use Models\Entities\Entity;

trait EntityToArray
{
	abstract public function NewObject(array $data);


	/**
	 * @return Entity[]
	 */
	public static function ToArray(array $datas): array {
		if ($datas === []) {
			return [];
		}

		$Array = [];
		foreach ($datas as $data) {
			if (!is_array($data)) {
				continue;
			}
			$Array[] = self::newObject($data);
		}
		return $Array;
	}
}