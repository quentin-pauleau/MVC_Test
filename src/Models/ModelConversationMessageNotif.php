<?php
namespace Models;

use Exception;
use PDO;

use Traits\Singleton;

use Models\Entities\ConversationMessageNotif;
use Models\EntityLists\ListConversationMessageNotif;

use Core\Database\DatabaseException;
use Core\Database\DatabaseQueryParam;
use Core\Database\ListDatabaseQueryParam;


/**
 * This class is the model for the {@see ConversationMessageNotif} database object
 * 
 * database object : {@see ConversationMessageNotif}
 * list : {@see ListConversationMessageNotif}
 */
final class ModelConversationMessageNotif extends Model
{
	use Singleton;

	/**
	 * @param array $data
	 * @return ConversationMessageNotif
	 */
	public function NewObject(array $data): ConversationMessageNotif {
		$ConversationMessageNotif = new ConversationMessageNotif();

		$ConversationMessageNotif->id ??= $data['CONVERSATION_MESSAGE_NOTIF_ID'];

		if (isset($data['CONVERSATION_MESSAGE_NOTIF_CREATIONDATE'])) {
			$ConversationMessageNotif->SetReadAtFromString($data['CONVERSATION_MESSAGE_NOTIF_CREATIONDATE']);
		}

		$ConversationMessageNotif->id_participant ??= $data['CONVERSATION_MESSAGE_NOTIF_IDPARTICIPANT'];
		$ConversationMessageNotif->id_message ??= $data['CONVERSATION_MESSAGE_NOTIF_IDMESSAGE'];

		$ConversationMessageNotif->is_read ??= $data['CONVERSATION_MESSAGE_NOTIF_ESTLU'];
		$ConversationMessageNotif->is_notified ??= $data['CONVERSATION_MESSAGE_NOTIF_ESTNOTIFIE'];

		return $ConversationMessageNotif;
	}

	
	/**
	 * @param array $data
	 * @return ListConversationMessageNotif
	 */
	public function NewList(array $data): ListConversationMessageNotif {
		$List = new ListConversationMessageNotif();

		foreach ($data as $ConversationMessageNotif)
			$List->Add($this->NewObject($ConversationMessageNotif));

		return $List;
	}

	#region Selection Queries

	public function GetById(int $id): ?ConversationMessageNotif {
		try
		{
			$data = $this->Database->GetOne(
				'SELECT * FROM conversation_message_notif WHERE CONVERSATION_MESSAGE_NOTIF_ID = :id', 
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
	 * Get all messages notifications
	 * @return ListConversationMessageNotif
	 */
	public function GetAll() : ListConversationMessageNotif {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					'SELECT * FROM conversation_message_notif'
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
				'SELECT CONVERSATION_MESSAGE_NOTIF_ID FROM conversation_message_notif WHERE CONVERSATION_MESSAGE_NOTIF_ID = :id', 
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
	 * Get all messages notifications
	 * @param 
	 * @return ListConversationMessageNotif
	 */
	public function GetByMessageId(int $id_message) : ListConversationMessageNotif {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					'SELECT * FROM conversation_message_notif WHERE CONVERSATION_MESSAGE_NOTIF_IDMESSAGE = :id_message',
					new ListDatabaseQueryParam(
						new DatabaseQueryParam(":id_message", $id_message, PDO::PARAM_INT)
					)
				)
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}
	

	/**
	 * Get all messages notifications
	 * @param 
	 * @return ListConversationMessageNotif
	 */
	public function GetByParticipantId(int $id_participant) : ListConversationMessageNotif {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					'SELECT * FROM conversation_message_notif WHERE CONVERSATION_MESSAGE_NOTIF_IDPARTICIPANT = :id_participant',
					new ListDatabaseQueryParam(
						new DatabaseQueryParam(":id_participant", $id_participant, PDO::PARAM_INT)
					)
				)
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}
	

	/**
	 * Get all messages notifications
	 * @param 
	 * @return ListConversationMessageNotif
	 */
	public function GetByConversationThreadId(int $id_thread) : ListConversationMessageNotif {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					'SELECT * FROM conversation_message_notif 
					LEFT JOIN conversation_message on conversation_message.CONVERSATION_MESSAGE_ID = CONVERSATION_MESSAGE_NOTIF_IDMESSAGE
					WHERE CONVERSATION_MESSAGE_IDFIL = :id_thread',
					new ListDatabaseQueryParam(
						new DatabaseQueryParam(":id_thread", $id_thread, PDO::PARAM_INT)
					)
				)
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}
	

	/**
	 * Get all messages notifications
	 * @param int $id_thread
	 * @return ListConversationMessageNotif
	 */
	public function GetNewByConversationThreadId(int $id_thread) : ListConversationMessageNotif {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					'SELECT * FROM conversation_message_notif 
					LEFT JOIN conversation_message on conversation_message.CONVERSATION_MESSAGE_ID = CONVERSATION_MESSAGE_NOTIF_IDMESSAGE 
					WHERE CONVERSATION_MESSAGE_IDFIL = :id_thread 
					AND CONVERSATION_MESSAGE_NOTIF_ESTLU = 0
					',
					new ListDatabaseQueryParam(
						new DatabaseQueryParam(":id_thread", $id_thread, PDO::PARAM_INT),
					)
				)
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}
	

	/**
	 * Get all messages notifications
	 * @param 
	 * @return ListConversationMessageNotif
	 */
	public function GetByUserId(int $id_user) : ListConversationMessageNotif {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					'SELECT * FROM conversation_message_notif 
					LEFT JOIN conversation_participant on CONVERSATION_PARTICIPANT_ID = CONVERSATION_MESSAGE_NOTIF_IDPARTICIPANT 
					WHERE CONVERSATION_PARTICIPANT_IDUSER = :id_user 
					',
					new ListDatabaseQueryParam(
						new DatabaseQueryParam(":id_user", $id_user, PDO::PARAM_INT)
					)
				)
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}
	

	/**
	 * Get the new messages notifications recieved by a user
	 * @param int $id_user ID of the user
	 * @return ListConversationMessageNotif
	 */
	public function GetNewByUserId(int $id_user) : ListConversationMessageNotif {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					'SELECT * FROM conversation_message_notif 
					LEFT JOIN conversation_participant on CONVERSATION_PARTICIPANT_ID = CONVERSATION_MESSAGE_NOTIF_IDPARTICIPANT 
					WHERE CONVERSATION_PARTICIPANT_IDUSER = :id_user 
					AND CONVERSATION_MESSAGE_NOTIF_ESTLU = 0 
					',
					new ListDatabaseQueryParam(
						new DatabaseQueryParam(":id_user", $id_user, PDO::PARAM_INT)
					)
				)
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}
	

	/**
	 * Get all messages notifications
	 * @param 
	 * @return ListConversationMessageNotif
	 */
	public function GetNewByThreadIdAndUserId(int $id_thread, int $id_user) : ListConversationMessageNotif {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					'SELECT * FROM conversation_message_notif 
					LEFT JOIN conversation_message on CONVERSATION_MESSAGE_ID = CONVERSATION_MESSAGE_NOTIF_IDMESSAGE 
					LEFT JOIN conversation_participant on CONVERSATION_PARTICIPANT_ID = CONVERSATION_MESSAGE_NOTIF_IDPARTICIPANT 
					WHERE CONVERSATION_MESSAGE_IDFIL = :id_thread 
					AND CONVERSATION_PARTICIPANT_IDUSER = :id_user 
					AND CONVERSATION_MESSAGE_NOTIF_ESTLU = 0 
					',
					new ListDatabaseQueryParam(
						new DatabaseQueryParam(":id_thread", $id_thread, PDO::PARAM_INT),
						new DatabaseQueryParam(":id_user", $id_user, PDO::PARAM_INT),
						new DatabaseQueryParam(":", false, PDO::PARAM_BOOL)
					)
				)
			);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}
	

	/**
	 * Get all messages notifications
	 * @param 
	 * @return ?ConversationMessageNotif
	 */
	public function GetNewByMessageIdAndUserId(int $id_message, int $id_user) : ?ConversationMessageNotif {
		try
		{
			
			$data = $this->Database->GetOne(
				'SELECT * FROM conversation_message_notif 
				LEFT JOIN conversation_participant on CONVERSATION_PARTICIPANT_ID = CONVERSATION_MESSAGE_NOTIF_IDPARTICIPANT 
				WHERE CONVERSATION_MESSAGE_NOTIF_IDMESSAGE = :id_message 
				AND CONVERSATION_PARTICIPANT_IDUSER = :id_user 
				AND CONVERSATION_MESSAGE_NOTIF_ESTLU = 0 
				',
				new ListDatabaseQueryParam(
					new DatabaseQueryParam(":id_message", $id_message, PDO::PARAM_INT),
					new DatabaseQueryParam(":id_user", $id_user, PDO::PARAM_INT),
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
	
	#endregion Selection Queries


	#region Modification Queries 
	
	/**
	 * Summary of Insert
	 * @param \Models\Entities\ConversationMessageNotif[] $ArrayMessage
	 * @return bool
	 */
	public function Insert(ConversationMessageNotif ...$ArrayMessage): bool {
		if ($ArrayMessage === [])
			return true;
		
		try
		{
			$nb_inserts = 0;
			
			foreach ($ArrayMessage as $MessageNotif) {
				$MessageNotif->id = $this->Database->InsertOne(
					'INSERT INTO conversation_message_notif 
					(CONVERSATION_MESSAGE_NOTIF_IDPARTICIPANT, CONVERSATION_MESSAGE_NOTIF_IDMESSAGE,
					CONVERSATION_MESSAGE_NOTIF_ESTLU, CONVERSATION_MESSAGE_NOTIF_ESTNOTIFIE) 
					VALUES 
					(:id_participant, :id_message, :is_read, :is_notified)',
					new ListDatabaseQueryParam(
						new DatabaseQueryParam(':id_participant', $MessageNotif->id_participant, PDO::PARAM_INT), 
						new DatabaseQueryParam(':id_message', $MessageNotif->id_message, PDO::PARAM_INT),
						new DatabaseQueryParam(':is_read', $MessageNotif->is_read, PDO::PARAM_BOOL),
						new DatabaseQueryParam(':is_notified', $MessageNotif->is_notified, PDO::PARAM_BOOL),
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


	public function InsertList(ListConversationMessageNotif $List): bool {
		if ($List->IsEmpty()) {
			return true;
		}
		
		try
		{
			$nb_inserts = 0;
			$query = 'INSERT INTO conversation_message_notif 
					(CONVERSATION_MESSAGE_NOTIF_IDMESSAGE, CONVERSATION_MESSAGE_NOTIF_IDPARTICIPANT,
					CONVERSATION_MESSAGE_NOTIF_ESTLU, CONVERSATION_MESSAGE_NOTIF_ESTNOTIFIE) 
					VALUES 
				';
			
			$QueryParams = new ListDatabaseQueryParam();
			
			foreach ($List as $key => $MessageNotif) {
				$query .= " (:id_message_$key, :id_participant_$key, :is_read_$key, :id_notified_$key), ";
				$QueryParams->Add(
					new DatabaseQueryParam(":id_message_$key", $MessageNotif->id_message, PDO::PARAM_INT),
					new DatabaseQueryParam(":id_participant_$key", $MessageNotif->id_participant, PDO::PARAM_INT), 
					new DatabaseQueryParam(":is_read_$key", $MessageNotif->is_read, PDO::PARAM_BOOL),
					new DatabaseQueryParam(":id_notified_$key", $MessageNotif->is_notified, PDO::PARAM_BOOL),
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


	/**
	 * Update Notifications
	 * @param \Models\Entities\ConversationMessageNotif[] $Notifs
	 * @return bool
	 */
	public function Update(ConversationMessageNotif ...$Notifs): bool {
		if ($Notifs === []) {
			return true;
		}
		
		try
		{
			$query = '';
			$QueryParams = new ListDatabaseQueryParam();

			foreach ($Notifs as $key => $Notif) {
				$query .= "UPDATE conversation_message_notif SET 
				CONVERSATION_MESSAGE_NOTIF_ESTLU = :is_read_$key 
				WHERE CONVERSATION_MESSAGE_NOTIF_ID = :id_$key; ";
				
				$QueryParams->Add(
					new DatabaseQueryParam(":id_$key", $Notif->id, PDO::PARAM_INT), 
					new DatabaseQueryParam(":is_read_$key", $Notif->is_read, PDO::PARAM_BOOL), 
				);
			}

			$nb_updates = $this->Database->Update(
				$query,
				$QueryParams
			);

			return $nb_updates === count($Notifs);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}

	/**
	 * Update Notifications
	 * @param \Models\Entities\ConversationMessageNotif[] $Notifs
	 * @return bool
	 */
	public function SetToRead(ConversationMessageNotif ...$Notifs): bool {
		if ($Notifs === [])
			return true;
		
		try
		{
			$query = '';
			$QueryParams = new ListDatabaseQueryParam();

			foreach ($Notifs as $key => $Notif) {
				$query .= "UPDATE conversation_message_notif SET 
				CONVERSATION_MESSAGE_NOTIF_ESTLU = 1 
				-- CONVERSATION_MESSAGE_NOTIF_DATELU = NOW() 
				WHERE CONVERSATION_MESSAGE_NOTIF_ID = :id_$key; ";
				
				$QueryParams->Add(
					new DatabaseQueryParam(":id_$key", $Notif->id, PDO::PARAM_INT), 
				);

				$Notif->is_read = true;
			}

			$nb_updates = $this->Database->Update(
				$query,
				$QueryParams
			);

			return $nb_updates === count($Notifs);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}
	
	public function Delete(ConversationMessageNotif ...$Notifs): bool {
		throw new Exception('Not implemented yet');
	}
	
	public function EraseById(int $id): bool {
		throw new Exception('Not implemented yet');
	}

	#endregion
}


