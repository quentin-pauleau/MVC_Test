<?php
namespace Modules\HTMLElement\Components\DataDisplay;

use Modules\HTMLElement\Components\ComponentColors;
use Modules\HTMLElement\HTMLElement;


class ChatBubble extends HTMLElement
{
	protected bool $isOnLeft;
	protected ComponentColors|null $Color = null;

	protected Avatar|null $avatar = null;

	protected string|null $authorName = null;

	protected string $message;

	protected string|null $sentAt = null;
	protected string|null $readAt = null;

	public function __construct(
		string|null $id = null,
		bool $isOnLeft = false,
		ComponentColors|null $color = null,
	)
	{
		parent::__construct($id);
		$this->isOnLeft = $isOnLeft;
		$this->Color = $color;
		
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

	
	public function setPosition(bool $isOnLeft): static
	{
		$this->isOnLeft = $isOnLeft;
		return $this;
	}


	public function setColor(ComponentColors $color): static
	{
		$this->Color = $color;
		return $this;
	}


	public function setAvatar(Avatar|null $avatar): static
	{
		$this->avatar = $avatar;
		return $this;
	}


	public function setAuthorName(string $authorName): static
	{
		$this->authorName = $authorName;
		return $this;
	}


	public function setMessage(string $message): static
	{
		$this->message = $message;
		return $this;
	}


	public function setHasDelivery(string $sentAt): static
	{
		$this->sentAt = $sentAt;
		return $this;
	}

	public function setHasReadAt(string $readAt): static
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
		return "chat-bubble-{$this->Color}";
	}
}