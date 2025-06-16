<?php
namespace Controllers;

use Controllers\Actions\ActionsMenu;        
use DateTime;
use Exception;

use Controllers\Controller;
use Controllers\Actions\ActionsConversation;

use Models\Entities\CDEClientH;
use Models\Entities\CDEFourH;
use Models\Entities\ConversationMessage;
use Models\Entities\ConversationParticipant;
use Models\Entities\ConversationThread;
use Models\Entities\DemandeDiverse;

use Models\ModelCDEClientH;
use Models\ModelCDEFourH;
use Models\ModelDemandeDiverse;

use Services\ServicesConversation\ServiceConversationMessage;
use Services\ServicesConversation\ServiceConversationParticipant;
use Services\ServicesConversation\ServiceConversationThread;
use Services\ServicesConversation\FormServiceConversationMessage;
use Traits\Singleton;

use Utils\Database\Database;
use Utils\Requests\Request;
use Utils\Responses\HTMLResponse;
use Utils\Responses\RewindRedirectionResponse;
use Utils\Responses\Response;

use Utils\Responses\RouteRedirectionResponse;
use Utils\Responses\URIRedirectionResponse;
use Utils\Session\DataHelper;
use Utils\Session\ErrorHelper;
use Utils\Session\UserHelper;

class ControllerConversation extends Controller
{
	use Singleton;

	public function Route($action): Response {
		switch ($action) {
			case ActionsConversation::EXIT:
				return self::Exit();

			case ActionsConversation::INIT:
				return self::Init();

			case ActionsConversation::THREAD:
				return self::Thread();

			case ActionsConversation::NEW_MESSAGE_PROCESS:
				return self::NewMessageProcess();

			default:
				return self::Thread();
		}
	}


	public function Exit(): Response {
		ErrorHelper::Clear();
		DataHelper::UnSet('CurrentMessage');
		DataHelper::UnSet('thread');

		$return_route = DataHelper::Get('return_route');
		DataHelper::UnSet('return_route');

		if ($return_route !== null)
			return new URIRedirectionResponse($return_route);

		return new RouteRedirectionResponse(ControllerMenu::class, ActionsMenu::MAIN);
	}


	public function Init(): Response {
		$Database = new Database;
		$Request = Request::GetRequest();

		$Thread = new ConversationThread;
		$Thread->title = "Conversation du ".(new DateTime())->format('d M Y H:i:s');

		$Database->BeginTransaction();

		try
		{
			//* create the conversation thread
			$Thread = (new ServiceConversationThread)->New($Thread);
		}
		catch(Exception $e)
		{
			$Database->RollBackTransaction();
			ErrorHelper::Set('insertion', $e);

			return new RewindRedirectionResponse;
		}

		//* Set information relative to the Conversation Subject
		if ($Request->Query->Has('subject_type') && $Request->Query->Has('subject_id')) {
			$subjectType = $Request->Query->FilterString("subject_type") ?? null;
			$subjectId = $Request->Query->FilterInt("subject_id") ?? null;

			try
			{
				switch ($subjectType) {
					case ConversationThread::SUBJECT_TYPE_CDE_CLIENT:
						(new ServiceConversationThread)->SetCDEClientHAsConversationSubject($Thread, $subjectId);
						break;

					case ConversationThread::SUBJECT_TYPE_CDE_FOUR:
						(new ServiceConversationThread)->SetCDEFourHAsConversationSubject($Thread, $subjectId);
						break;

					case ConversationThread::SUBJECT_TYPE_DEMANDE_DIVERSE:
						(new ServiceConversationThread)->SetDemandeDiverseAsConversationSubject($Thread, $subjectId);
						break;

					default:
						throw new Exception("the subject of conversation '$subjectType' is not supported yet");        
				}

				DataHelper::Set("subject_type", $Thread->ConversationSubject::GetConversationSubjectType());
				DataHelper::Set("subject_id", $Thread->ConversationSubject->GetId());
			}
			catch (Exception $e)
			{
				$Database->RollBackTransaction();
				ErrorHelper::SetDefault('insertion', 'Impossible d\'associer le sujet de conversation à la discussion'.$e);
				ErrorHelper::SetDebug('insertion', $e);

				return new RewindRedirectionResponse;
			}
		}

		//* add the user as a participant of the conversation
		if (!$Thread->IsParticipant(UserHelper::GetUserId()))
			$Thread->Participants->Add(
				(new ServiceConversationParticipant)->New(
					new ConversationParticipant(
						$Thread->id,
						UserHelper::GetUserId(),
					)
				)
			);

		if (!$Database->CommitTransaction()) {
			ErrorHelper::SetDefault('thread', 'Impossible de créer la conversation');
			return new RewindRedirectionResponse;
		}

		ErrorHelper::Clear();
		DataHelper::Set("thread", $Thread);

		return new RouteRedirectionResponse(self::class, ActionsConversation::THREAD);
	}


	public function Thread(): Response
	{
		//* verify if the conversation thread id is valid
		$Request = Request::GetRequest();

		$Thread = DataHelper::Get('thread');


		if ($Thread === null) {
			$id = null;

			switch ($id = $Request->Query->FilterInt("id") ?? DataHelper::Get("thread_id")) {
				case null:
					ErrorHelper::SetDefault('conversation', 'Impossible de récuperer le fil de conversation');      
					return new RewindRedirectionResponse;

				case false:
					ErrorHelper::SetDefault('conversation', 'Identifiant du fil de conversation invalide');
					return new RewindRedirectionResponse;
			}

			DataHelper::Set('thread_id', $id);

			try
			{
				$ServiceConversationThread = new ServiceConversationThread;

				$Thread = $ServiceConversationThread->Find($id);

				//* Get the conversation thread subject if there is one
				$ServiceConversationThread->AssociateConversationSubject($Thread);
			}
			catch (Exception $e)
			{
				ErrorHelper::SetDefault('conversation', 'Impossible de récupérer la conversation');
				ErrorHelper::SetDebug('conversation', $e);

				return new RewindRedirectionResponse;
			}

			DataHelper::Set('thread', $Thread);
		}


		//* set the return route if not existing
		if (!DataHelper::IsSet('return_route'))
			DataHelper::Set('return_route', $Request->Server->GetReferer());

		//* if the thread is closed display is non participant view
		return new HTMLResponse(
			"public/Views/conversation/conversation_thread.php",
			[
				'Thread' => $Thread,
				'CurrentMessage' => DataHelper::Get("CurrentMessage") ?? new ConversationMessage,
				'errors' => ErrorHelper::GetAll(),
			]
		);
	}


	/**
	 * Summary of NewMessageProcess
	 * @throws \Exception
	 * @return RouteRedirectionResponse
	 */
	public function NewMessageProcess(): Response
	{
		//* verify if the thread id is valid
		$Request = Request::GetRequest();

		$Thread = DataHelper::Get('thread');

		if (!($Thread instanceof ConversationThread) || $Thread->id === null) {
			ErrorHelper::Set('conversation', 'The conversation thread is not set or is invalid');
			return new RewindRedirectionResponse;
		}

		$Message = new ConversationMessage;

		if (!(new FormServiceConversationMessage)->Handle($Request, $Message)) {
			DataHelper::Set('CurrentMessage', $Message);
			return new RouteRedirectionResponse(self::class, ActionsConversation::THREAD);
		}

		$Message->id_thread = $Thread->id;

		$Database = new Database;
		$Database->BeginTransaction();

		//* find/create the author of the message
		$ServiceConversationParticipant = new ServiceConversationParticipant;
		try
		{
			$Message->Author = $ServiceConversationParticipant->FindByUserAndThread(
				UserHelper::GetUserId(),
				$Thread->id,
			);
		}
		catch (Exception $e)
		{
			//* the author isnt a participant of the thread
			//* create a new participant for the author
			$Message->Author = $ServiceConversationParticipant->New(
				new ConversationParticipant(
					$Thread->id,
					UserHelper::GetUserId(),
				)
			);

			// Add the new participant to the thread
			$Thread->Participants->Add($Message->Author);
			DataHelper::Set('thread', $Thread);
		}

		//* set the author id correctly
		$Message->id_author = $Message->Author->id;

		try
		{
			(new ServiceConversationMessage)->New($Message);
		}
		catch (Exception $e)
		{
			$Database->RollBackTransaction();
			DataHelper::Set('CurrentMessage', $Message);

			ErrorHelper::SetDefault('CurrentMessage', 'Une erreur est survenue lors de l\'envois du message');
			ErrorHelper::SetDebug('CurrentMessage', $e);

			return new RouteRedirectionResponse(self::class, ActionsConversation::THREAD);
		}

		$Database->CommitTransaction();

		DataHelper::UnSet('CurrentMessage');
		ErrorHelper::Clear();

		return new RouteRedirectionResponse(self::class, ActionsConversation::THREAD);
	}
}