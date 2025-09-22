<?php
namespace Modules\ExpendedReflection;



class ClassReflection
{
	private $_reflection;
	private string $_target;

	public function __construct(string|object $target)
	{
		$this->_target = (is_object($target)) ? get_class($target) : $target;

		if (!class_exists($this->_target))
			throw new \Exception("Class {$this->_target} not found");

		$this->_reflection = new \ReflectionClass($this->_target);
	}


	#region Attributes

	public function GetAttributes(): array {
		return $this->_reflection->getAttributes();
	}

	public function GetAttributesByName(string $name): array {
		$attributes = [];

		foreach ($this->GetAttributes() as $attribute)
			if ($attribute->getName() === $name)
				$attributes[] = $attribute;
		
		return $attributes;
	}

	public function HasAttribute(string $name): bool {
		return $this->GetAttributesByName($name) !== [];
	}
	#endregion Attributes


	
	#region Interfaces
	public function GetInterfaces(): array {
		return $this->_reflection->getInterfaceNames();
	}

	public function GetInterface(string $name): mixed {
		foreach ($this->GetInterfaces() as $interface)
			if ($interface === $name)
				return $interface;
		
		return null;
	}

	public function HasInterface(string $name): bool {
		return in_array($name, $this->GetInterfaces());
	}
	

	#region Constants

	public function GetConstants(): array {
		return $this->_reflection->getConstants();
	}

	public function GetConstant(string $name): mixed {
		return $this->_reflection->getConstant($name);
	}




	public function GetProperties(): array {
		return $this->_reflection->getProperties();
	}

	public function GetMethods(): array {
		return $this->_reflection->getMethods();
	}
}