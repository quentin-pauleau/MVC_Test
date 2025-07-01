<?php
namespace Modules\HTMLElement\Components\DataDisplay;

use Modules\HTMLElement\Components\ComponentColors;
use Modules\HTMLElement\HTMLElement;


class ChatBubble extends HTMLElement
{
	protected bool $isOnLeft;
	protected string $color;

	protected ?Avatar $avatar;

	protected ?string $authorName;

	protected string $message;

	protected string|null $sentAt;
	protected string|null $readAt;

	public function __construct(array $childs, ?string $id = null)
	{
		
	}


	public function __toString(): string
	{
		return <<<HTML
		<div class="chat {$this->getDirectionClass()}">
			{$this->avatar}
			{$this->getHeader()}
			<div class="chat-bubble {$this->getColorClass()}">
				{$this->message}
			</div>
			{$this->getFooter()}
		</div>
		HTML;
	}


	public function getHeader(): string
	{
		return <<<HTML
		<div class="chat-header">
			{$this->authorName}
			{$this->getDate($this->sentAt)}
		</div>
		HTML;
	}


	public function getDate(string|null $date): string {
		if ($date === null)
			return '';

		return <<<HTML
		<time class="text-xs opacity-50">{$date}</time>
		HTML;
	}


	public function getFooter(): string
	{
		if ($this->readAt === null)
			return '';

		return <<<HTML
		<div class="chat-footer opacity-50">
			Seen at {$this->getDate($this->readAt)}
		</div>
		HTML;
	}

	
	public function setPosition(bool $isOnLeft): self
	{
		$this->isOnLeft = $isOnLeft;
		return $this;
	}


	public function setColor(ComponentColors $color): self
	{
		$this->color = $color->value;
		return $this;
	}


	public function setAvatar(Avatar|null $avatar): self
	{
		$this->avatar = $avatar;
		return $this;
	}


	public function setAuthorName(string $authorName): self
	{
		$this->authorName = $authorName;
		return $this;
	}


	public function setMessage(string $message): self
	{
		$this->message = $message;
		return $this;
	}


	public function setHasDelivery(string $sentAt): self
	{
		$this->sentAt = $sentAt;
		return $this;
	}

	public function setHasReadAt(string $readAt): self
	{
		$this->readAt = $readAt;
		return $this;
	}


	private function getDirectionClass(): string
	{
		return $this->isOnLeft ? 'chat-start' : 'chat-end';
	}

	private function getColorClass(): string
	{
		return "chat-bubble-{$this->color}";
	}
}