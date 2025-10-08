<?php
namespace Modules\Serialisation;



interface SerializerInterface
{
	public static function Serialize(array $data): self;
	public function Deserialize(): mixed;
}