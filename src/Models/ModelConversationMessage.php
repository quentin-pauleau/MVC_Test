<?php
namespace Models;

use Exception;
use PDO;

use Traits\Singleton;

use Models\Entities\ConversationMessage;
use Models\EntityLists\ListConversationMessage;

use Core\Database\DatabaseException;
use Core\Database\DatabaseQueryParam;
use Core\Database\ListDatabaseQueryParam;


/**
 * This class is the model for the {@see ConversationMessage} database object
 * 
 * database object : {@see ConversationMessage}
 * list : {@see ListConversationMessage}
 */
final class ModelConversationMessage extends Model
{
	use Singleton;

	public function NewObject(array $data): ConversationMessage {
		$ConversationMessage = new ConversationMessage();

		$ConversationMessage->id ??= $data['CONVERSATION_MESSAGE_ID'];

		if (isset($data['CONVERSATION_MESSAGE_CREATIONDATE']))
			$ConversationMessage->SetCreatedAtFromString($data['CONVERSATION_MESSAGE_CREATIONDATE']);

		$ConversationMessage->id_author ??= $data['CONVERSATION_MESSAGE_IDPARTICIPANT'];
		$ConversationMessage->id_thread ??= $data['CONVERSATION_MESSAGE_IDFIL'];

		if (isset($data['CONVERSATION_MESSAGE_CONTENU']))
			$ConversationMessage->content = Convert_encoding_to_utf8($data['CONVERSATION_MESSAGE_CONTENU']);

		$ConversationMessage->is_important ??= $data['CONVERSATION_MESSAGE_ESTIMPORTANT'];

		if (isset($data['CONVERSATION_MESSAGE_UUID']))
			$ConversationMessage->UUID->SetUUIDFromString($data['CONVERSATION_MESSAGE_UUID']);

		return $ConversationMessage;
	}

	
	public function NewList(array $data): ListConversationMessage {
		$List = new ListConversationMessage();

		foreach ($data as $ConversationMessage) {
			$List->Add($this->NewObject($ConversationMessage));
		}

		return $List;
	}

	#region Selection Queries

	/**
	 * @param int $id
	 * @return ConversationMessage|null
	 */
	public function GetById(int $id): ?ConversationMessage {
		try
		{
			
			$data = $this->Database->GetOne(
				'SELECT * FROM conversation_message WHERE CONVERSATION_MESSAGE_ID = :id', 
				new ListDatabaseQueryParam(
					new DatabaseQueryParam(':id', $id, PDO::PARAM_INT)
				)
			);

			if ($data === [])
				return null;

			return $this->NewObject($data);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}

	/**
	 * Get all commandes fours
	 * @param 
	 * @return ListConversationMessage
	 */
	public function GetAll() : ListConversationMessage {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					'SELECT * FROM conversation_message'
				)
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}

	
	public function Any(int $id): bool {
		try
		{
			
			$data = $this->Database->GetOne(
				'SELECT * FROM conversation_message WHERE CONVERSATION_MESSAGE_ID = :id', 
				new ListDatabaseQueryParam(
					new DatabaseQueryParam(':id', $id, PDO::PARAM_INT)
				)
			);

			return $data !== [];
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	/**
	 * Get all commandes fours
	 * @param 
	 * @return ListConversationMessage
	 */
	public function GetByThreadId(int $id_fil) : ListConversationMessage {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					'SELECT * FROM conversation_message WHERE CONVERSATION_MESSAGE_IDFIL = :id_fil',
					new ListDatabaseQueryParam(
						new DatabaseQueryParam(":id_fil", $id_fil, PDO::PARAM_INT)
					)
				)
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}
	
	#endregion


	#region Modification Queries
	
	/**
	 * Summary of Insert
	 * @param \Models\Entities\ConversationMessage[] $ArrayMessage
	 * @return bool
	 */
	public function Insert(ConversationMessage ...$ArrayMessage): bool {
		if ($ArrayMessage === [])
			return true;
		
		try
		{
			$nb_inserts = 0;
			
			foreach ($ArrayMessage as $Message) {
				$Message->id = $this->Database->InsertOne(
					'INSERT INTO conversation_message 
					(CONVERSATION_MESSAGE_CREATIONDATE, CONVERSATION_MESSAGE_IDPARTICIPANT, CONVERSATION_MESSAGE_IDFIL,
					CONVERSATION_MESSAGE_CONTENU, CONVERSATION_MESSAGE_ESTIMPORTANT, CONVERSATION_MESSAGE_UUID) 
					VALUES 
					(NOW(), :id_participant, :id_thread, :content, :is_important, :uuid)',
					new ListDatabaseQueryParam(
						new DatabaseQueryParam(':id_participant', $Message->id_author, PDO::PARAM_INT), 
						new DatabaseQueryParam(':id_thread', $Message->id_thread, PDO::PARAM_INT),
						new DatabaseQueryParam(':content', Convert_encoding_to_iso($Message->content), PDO::PARAM_STR),
						new DatabaseQueryParam(':is_important', $Message->is_important, PDO::PARAM_BOOL),
						new DatabaseQueryParam(':uuid', $Message->UUID, PDO::PARAM_STR),
					)
				);
				
				$nb_inserts++;
			}
			
			return $nb_inserts === count($ArrayMessage);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	public function InsertList(ListConversationMessage $List): bool {
		if ($List->IsEmpty()) {
			return true;
		}
		
		try
		{
			$nb_inserts = 0;
			$query = 'INSERT INTO conversation_message 
					(CONVERSATION_MESSAGE_CREATIONDATE, CONVERSATION_MESSAGE_IDPARTICIPANT, CONVERSATION_MESSAGE_IDFIL,
					CONVERSATION_MESSAGE_CONTENU, CONVERSATION_MESSAGE_ESTIMPORTANT, CONVERSATION_MESSAGE_UUID) 
					VALUES 
				';
			$QueryParams = new ListDatabaseQueryParam();
			
			foreach ($List as $key => $Message) {
				$query .= " (NOW(), :id_participant_$key, :id_thread_$key, :content_$key, :is_important_$key, :uuid_$key), ";
				$QueryParams->Add(
					new DatabaseQueryParam(":id_participant_$key", $Message->id_author, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_thread_$key", $Message->id_thread, PDO::PARAM_INT),
					new DatabaseQueryParam(":content_$key", Convert_encoding_to_iso($Message->content), PDO::PARAM_STR),
					new DatabaseQueryParam(":is_important_$key", $Message->is_important, PDO::PARAM_BOOL),
					new DatabaseQueryParam(":uuid_$key", $Message->UUID, PDO::PARAM_STR),
				);
				
				$nb_inserts++;
			}
			
			if (str_ends_with2($query, ", ")) {
				$query = rtrim($query, ", ");
			}

			$nb_inserts = $this->Database->InsertList(
				$query,
				$QueryParams
			);

			return $nb_inserts === count($List);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	public function Update(): bool {
		throw new Exception('Not implemented yet');
	}
	
	
	public function Delete(ConversationMessage ...$ArrayConversationMessage): bool {
		throw new Exception('Not implemented yet');
	}
	
	public function EraseById(int $id): bool {
		throw new Exception('Not implemented yet');
	}

	#endregion
}


