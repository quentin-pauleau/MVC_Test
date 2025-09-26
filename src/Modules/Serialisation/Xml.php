<?php
namespace Modules\Serialisation;

/**
 * XML value object providing serialization and deserialization similar to Json.
 *
 * Notes:
 * - Serialization accepts arrays/objects and converts them to XML with a root node
 * - Deserialization returns associative arrays by default
 * - This is intentionally lightweight and avoids extensions beyond core SimpleXML
 */
final readonly class Xml
{
	public function __construct(
		private string $xml,
	) {}

	/**
	 * Serialize an array/object into XML.
	 * @param array|object $data
	 * @param string $rootNodeName Root element name (default: root)
	 */
	public static function Serialize(array|object $data, string $rootNodeName = 'root'): self
	{
		$arrayData = is_object($data) ? self::objectToArray($data) : $data;

		if (!is_array($arrayData))
			throw new \InvalidArgumentException('XML serialization expects array|object');

		$xml = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><{$rootNodeName}/>");

		self::arrayToXml($arrayData, $xml);
		
		// AsXML returns string|false
		$xmlString = $xml->asXML() ?: throw new \RuntimeException('XML serialization failed');
		
		return new self($xmlString);
	}

	/**
	 * Deserialize XML into an associative array.
	 * If $class is provided, tries to hydrate like Json::DeserializeObject.
	 */
	public function Deserialize(?string $class = null): mixed
	{
		$simple = @simplexml_load_string($this->xml, 'SimpleXMLElement', LIBXML_NOCDATA);
		if ($simple === false)
			throw new \RuntimeException('XML deserialization failed');

		$json = json_encode($simple, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		$array = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

		if ($class === null)
			return $array;

		// Reuse Json hydrator logic by wrapping
		$jsonVo = new Json(json_encode($array, JSON_THROW_ON_ERROR));
		return $jsonVo->DeserializeObject($class);
	}

	public function __toString(): string
	{
		return $this->xml;
	}

	// --- helpers ---

	private static function objectToArray(object $obj): array
	{
		// Convert public properties (and nested objects) into an array
		return json_decode(
			json_encode($obj, JSON_THROW_ON_ERROR),
			true,
			512,
			JSON_THROW_ON_ERROR
		);
	}

	private static function arrayToXml(array $data, \SimpleXMLElement $xml): void
	{
		foreach ($data as $key => $value) {
			// Ensure valid XML tag names
			$tag = is_string($key) && self::isValidXmlTag($key) ? $key : 'item';

			if (is_array($value)) {
				// Lists vs associative arrays
				if (array_is_list($value)) {
					foreach ($value as $v) {
						$child = $xml->addChild($tag);
						self::addValue($child, $v);
					}
				} else {
					$child = $xml->addChild($tag);
					self::arrayToXml($value, $child);
				}
			} else {
				$xml->addChild($tag, self::scalarToString($value));
			}
		}
	}

	private static function addValue(\SimpleXMLElement $node, mixed $value): void
	{
		if (is_array($value))
			self::arrayToXml($value, $node);
		else
			$node[0] = self::scalarToString($value);
	}

	private static function scalarToString(mixed $value): string
	{
		return match (true) {
			$value === null => '',
			is_bool($value) => $value ? 'true' : 'false',
			is_scalar($value) => (string)$value,
			default => json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: ''
		};
	}

	private static function isValidXmlTag(string $name): bool
	{
		// Basic validation: start with letter or underscore, then letters/digits/._-
		return (bool)preg_match('/^[A-Za-z_][A-Za-z0-9_\.-]*$/', $name);
	}
}