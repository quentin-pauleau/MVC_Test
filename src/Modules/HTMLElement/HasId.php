<?php
namespace Modules\HTMLElement;

trait HasId
{
	protected string|null $id = null;

	public function getId(): ?string
	{
		return $this->id;
	}

	public function setId(string $id): static
	{
		$this->id = $id;
		return $this;
	}

	public function hasId(): bool
	{
		return isset($this->id);
	}


	public function randomizeId(): static
	{
		$this->setId(uniqid());
		return $this;
	}
}