<?php
namespace Modules\Serialisation;


trait SerialisableRecord
{
	public function ToJson(): Json
	{
		return Json::Serialize(
			$this
		);
	}

	public function ToXml(): Xml
	{
		return Xml::Serialize(
			$this
		);
	}


	public static function FromJson(Json $json): self
	{
		$new = $json->Deserialize(self::class);

		($new instanceof self)
			?: throw new \Exception('Invalid type');

		return $new;
	}


	public static function FromXml(Xml $xml): self
	{
		$new = $xml->Deserialize(self::class);

		($new instanceof self)
			?: throw new \Exception('Invalid type');
		
		return $new;
	}

}