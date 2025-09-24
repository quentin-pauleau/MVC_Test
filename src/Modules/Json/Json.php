<?php
namespace Modules\Json;


final class Json
{
	public function __construct(
		private string $json,
	)
	{}


	public static function Serialize(mixed $object): Json
	{
		return new self(
			json_encode($object) ?: throw new \JsonException("Json serialization failed : ". json_last_error_msg())
		);
	}

	public function Deserialize(?string $class = null): mixed
	{
		if ($class !== null)
			return $this->DeserializeObject($class);

		return json_decode($this->json) ?? throw new \JsonException("Json deserialization failed: ". json_last_error_msg());
	}


	public function DeserializeObject(string $class): object|array|null
	{
		if (!class_exists($class))
			throw new \InvalidArgumentException("Class '{$class}' does not exist");

		$result = json_decode($this->json) ?? throw new \JsonException("Json deserialization failed: ". json_last_error_msg());

		return match (true) {
			is_object($result) 
				=> $this->GetDeserializedObject($result, $class),

			is_array($result) 
				=> $this->GetDeserializedObjectList($result, $class),
			
			$result === null 
				=> null,
			
			default 
				=> throw new \UnexpectedValueException("Deserialized value is neither an array nor an object"),
		};
	}


	private static function GetDeserializedObject(object $object, string $class): object
	{
		if (!$object instanceof $class)
			throw new \UnexpectedValueException("Deserialized value is not an instance of {$class}");

		return $object;
	}
	private static function GetDeserializedObjectList(array $list, string $class): object
	{
		foreach ($list as $object)
			yield self::GetDeserializedObject($object, $class);
		
		return;
	}


	public function __toString()
	{
		return $this->json;
	}
}