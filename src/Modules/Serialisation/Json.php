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

	public function Deserialize(?string $class = null): array
	{
		$decoded = json_decode($this->json, true, 512, JSON_THROW_ON_ERROR);

		if ($class === null)
			return $decoded;

		return $this->DeserializeObject($class);
	}


	public function DeserializeObject(string $class): object|array|null
	{
		if (!class_exists($class))
			throw new \InvalidArgumentException("Class '{$class}' does not exist");

		$decoded = json_decode($this->json, true, 512, JSON_THROW_ON_ERROR);

		if ($decoded === null)
			return null;

		if (is_array($decoded)) {
			if (array_is_list($decoded)) {
				$result = [];
				foreach ($decoded as $item) {
					if (!is_array($item))
						throw new \UnexpectedValueException('List item is not an object/associative array');
					
					$result[] = self::hydrateArrayToClass($item, $class);
				}
				return $result;
			}

			return self::hydrateArrayToClass($decoded, $class);
		}

		throw new \UnexpectedValueException('Deserialized value is neither an array nor an object');
	}


	private static function hydrateArrayToClass(array $data, string $class): object
	{
		$ref = new \ReflectionClass($class);

		// Try constructor-first hydration
		$ctor = $ref->getConstructor();
		if ($ctor && $ctor->getNumberOfParameters() > 0) {
			$args = [];
		foreach ($ctor->getParameters() as $param) {
			$name = $param->getName();
				if (array_key_exists($name, $data)) {
					$args[] = $data[$name];
				} elseif ($param->isDefaultValueAvailable()) {
					$args[] = $param->getDefaultValue();
				} else {
				// If param is required but missing, pass null (common in DTOs) – user code can validate later
				$args[] = null;
			}
		}
		return $ref->newInstanceArgs($args);
		}

		// Fallback to property assignment (public properties only)
		$obj = $ref->newInstance();
		foreach ($data as $k => $v) {
			if ($ref->hasProperty($k)) {
				$prop = $ref->getProperty($k);
				if ($prop->isPublic()) {
				$obj->$k = $v;
				}
			}
		}
		return $obj;
	}

	public function __toString(): string
	{
		return $this->json;
	}
}