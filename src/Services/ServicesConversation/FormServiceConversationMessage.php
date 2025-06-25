<?php
namespace Services\ServicesConversation;

use Interfaces\FormServiceInterface;
use Models\Entities\ConversationMessage;
use Core\Requests\Request;
use Core\Session\ErrorHelper;


class FormServiceConversationMessage implements FormServiceInterface
{
	public function __construct() {}

	public function Handle(Request $Request, object $CoversationMessage): bool
	{
		$result = true;

		if (!($CoversationMessage instanceof ConversationMessage))
			throw new \Exception('The ConversationMessage must be an isntace of ConversationMessage');

		switch ($content = $Request->Data->FilterString('message')) {
			case null:
				ErrorHelper::set("message", "Le message est obligatoire");
				$result = false;
				break;
	
			case false:
				ErrorHelper::set("message", "Le message est invalide");
				$result = false;
				break;
	
			default:
				$CoversationMessage->content = $content;
				break;
		}

		return $result;
	}
}