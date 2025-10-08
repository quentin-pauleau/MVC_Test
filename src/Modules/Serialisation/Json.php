<?php
namespace Modules\Serialisation;

/**
 * JSON value object with serialization/deserialization
 */
final readonly class Json implements SerializerInterface
{
	public function __construct(
		private string $json,
	) {}

	/**
	 * Serialize data to JSON string.
	 *
	 * - Uses JSON_THROW_ON_ERROR for reliable error reporting
	 * - Keeps Unicode and slashes unescaped for readability
	 */
	public static function Serialize(array|object $data): self
	{
		$json = json_encode(
			$data,
			JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
		);

		return new self($json);
	}

	public function Deserialize(?string $class = null): mixed
	{
		if ($class === null)
			return $this->DeserializeScalaire();

		return $this->DeserializeObject($class);
	}


	public function DeserializeScalaire(): mixed
	{
		return json_decode($this->json, true, 512, JSON_THROW_ON_ERROR);
	}


	private function DeserializeObject(string $class): object|array|null
	{
		class_exists($class) 
			?: throw new \InvalidArgumentException("Class '{$class}' does not exist");

		$decoded = json_decode($this->json, true, 512, JSON_THROW_ON_ERROR)
			?? throw new \JsonException('Failed to decode JSON');

		is_array($decoded)
			?: throw new \UnexpectedValueException('Deserialized value is neither an object or a list of object');

		if (!array_is_list($decoded))
			return self::hydrateArrayToClass($decoded, $class);

		$result = [];
		foreach ($decoded as $item) {
			is_array($item)
				?: throw new \UnexpectedValueException('List item is not an object/associative array');
			
			$result[] = self::hydrateArrayToClass($item, $class);
		}
		return $result;
	}


	private static function hydrateArrayToClass(array $data, string $class): object
	{
		$ref = new \ReflectionClass($class);

		// Try constructor-first hydration
		$constructor = $ref->getConstructor();
		if ($constructor && $constructor->getNumberOfParameters() > 0) {
			$args = [];
			foreach ($constructor->getParameters() as $param) {
				$name = $param->getName();

				$args[] = match (true) {
					isset($data[$name]) => $data[$name],
					$param->isDefaultValueAvailable() => $param->getDefaultValue(),
					default => null,
				};
			}
			return $ref->newInstanceArgs($args);
		}

		// Fallback to property assignment (public properties only)
		$obj = $ref->newInstance();
		foreach ($data as $k => $v) {
			if ($ref->hasProperty($k)) {
				$prop = $ref->getProperty($k);

				if ($prop->isPublic())
					$obj->$k = $v;
			}
		}
		return $obj;
	}

	public function __toString(): string
	{
		return $this->json;
	}
}