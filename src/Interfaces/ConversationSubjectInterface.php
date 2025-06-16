<?php
namespace Interfaces;

use DateTime;
use Models\Entities\ConversationThread;

interface ConversationSubjectInterface
{
	/**
	 * Get the type of the conversation subject
	 * @return void
	 */
	public static function GetConversationSubjectType(): string;

	/**
	 * Get the creation date of the conversation subject
	 * @return DateTime|null the creation date, null if the subject dont have any
	 */
	public function GetCreatedAt(): ?DateTime;

	/**
	 * Get the id of the conversation subject
	 * @return int|null the id of the conversation subject, null if the subject dont have one
	 */
	public function GetId(): ?int;

	/**
	 * Get the conversation thread the subject is linked to
	 * @return ConversationThread|null the conversation thread, null if the subject dont have one
	 */
	public function GetConversationThread(): ?ConversationThread;

	/**
	 * Get the id of the conversation thread the subject is linked to
	 * @return int|null the id of the conversation thread, null if the subject dont have one
	 */
	public function GetConversationThreadId(): ?int;
}