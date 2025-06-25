<?php
namespace Models;

use Exception;
use PDO;

use Traits\Singleton;

use Models\Entities\ConversationParticipant;
use Models\EntityLists\ListConversationParticipant;

use Core\Database\DatabaseException;
use Core\Database\DatabaseQueryParam;
use Core\Database\ListDatabaseQueryParam;


/**
 * This class is the model for the {@see ConversationParticipant} database object
 * 
 * database object : {@see ConversationParticipant}
 * list : {@see ListConversationParticipant}
 */
final class ModelConversationParticipant extends Model
{
	use Singleton;

	/**
	 * Create a new {@see ConversationParticipant} using data fetched from the database
	 * @param array $data
	 * @return ConversationParticipant
	 */
	public function NewObject(array $data): ConversationParticipant {
		$ConversationParticipant = new ConversationParticipant();

		$ConversationParticipant->id ??= $data['CONVERSATION_PARTICIPANT_ID'];

		if (isset($data['CONVERSATION_PARTICIPANT_CREATIONDATE'])) {
			$ConversationParticipant->SetCreatedAtFromString($data['CONVERSATION_PARTICIPANT_CREATIONDATE']);
		}

		$ConversationParticipant->id_user ??= $data['CONVERSATION_PARTICIPANT_IDUSER'];
		$ConversationParticipant->id_thread ??= $data['CONVERSATION_PARTICIPANT_IDFIL'];
		// $ConversationParticipant->id_state ??= $data['CONVERSATION_PARTICIPANT_IDETAT'];

		$ConversationParticipant->is_active ??= $data['CONVERSATION_PARTICIPANT_ACTIF'];

		return $ConversationParticipant;
	}

	/**
	 * Create a new {@see ListConversationParticipant} using data fetched from the database
	 * @param array $data
	 * @return ListConversationParticipant
	 */
	public function NewList(array $data): ListConversationParticipant {
		$List = new ListConversationParticipant();

		foreach ($data as $ConversationParticipant) {
			$List->Add($this->NewObject($ConversationParticipant));
		}

		return $List;
	}

	#region Selection Queries

	/**
	 * @param int $id
	 * @return ConversationParticipant|null
	 */
	public function GetById(int $id): ?ConversationParticipant {
		try
		{
			
			$data = $this->Database->GetOne(
				'SELECT * FROM conversation_participant WHERE CONVERSATION_PARTICIPANT_ID = :id', 
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
	 * Get all commandes four
	 * @return ListConversationParticipant
	 */
	public function GetAll() : ListConversationParticipant {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					'SELECT * FROM conversation_participant'
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
				'SELECT CONVERSATION_PARTICIPANT_ID FROM conversation_participant WHERE CONVERSATION_PARTICIPANT_ID = :id', 
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
	 * @param int $id_fil
	 * @return ListConversationParticipant
	 */
	public function GetByThreadId(int $id_fil) : ListConversationParticipant {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					'SELECT * FROM conversation_participant WHERE CONVERSATION_PARTICIPANT_IDFIL = :id_fil',
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

	
	/**
	 * Get by user id
	 * @param int $id_fil
	 * @return ListConversationParticipant
	 */
	public function GetByUserId(int $id_user) : ListConversationParticipant {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					'SELECT * FROM conversation_participant WHERE CONVERSATION_PARTICIPANT_IDUSER = :id_user',
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
	 * Get by user ID and thread ID
	 * @param int $id_user
	 * @param int $id_thread
	 * @return ConversationParticipant
	 */
	public function GetByUserIdAndThreadId(int $id_user, int $id_thread): ?ConversationParticipant {
		try
		{
			$data = $this->Database->GetOne(
				'SELECT * FROM conversation_participant 
				WHERE CONVERSATION_PARTICIPANT_IDFIL = :id_thread 
				AND CONVERSATION_PARTICIPANT_IDUSER = :id_user',
				new ListDatabaseQueryParam(
					new DatabaseQueryParam(":id_thread", $id_thread, PDO::PARAM_INT),
					new DatabaseQueryParam(":id_user", $id_user, PDO::PARAM_INT)
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
	
	#endregion


	#region Modification Queries
	
	/**
	 * Summary of Insert
	 * @param \Models\Entities\ConversationParticipant[] $ArrayParticipant
	 * @return bool
	 */
	public function Insert(ConversationParticipant ...$ArrayParticipant): bool {
		if ($ArrayParticipant === [])
			return true;
		
		try
		{
			$nb_inserts = 0;
			
			foreach ($ArrayParticipant as $Participant) {
				$Participant->id = $this->Database->InsertOne(
					'INSERT INTO conversation_participant 
					(CONVERSATION_PARTICIPANT_CREATIONDATE, 
					CONVERSATION_PARTICIPANT_IDUSER, CONVERSATION_PARTICIPANT_IDFIL, 
					CONVERSATION_PARTICIPANT_ACTIF) 
					VALUES 
					(NOW(), :id_user, :id_thread, :is_active)
					',
					new ListDatabaseQueryParam(
						new DatabaseQueryParam(':id_user', $Participant->id_user, PDO::PARAM_INT), 
						new DatabaseQueryParam(':id_thread', $Participant->id_thread, PDO::PARAM_INT),
						new DatabaseQueryParam(':is_active', $Participant->is_active, PDO::PARAM_BOOL),
					)
				);
				
				$nb_inserts++;
			}
			
			return $nb_inserts === count($ArrayParticipant);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	public function InsertList(ListConversationParticipant $List): bool {
		if ($List->IsEmpty())
			return true;
		
		try
		{
			$nb_inserts = 0;
			$query = 'INSERT INTO conversation_participant 
					(CONVERSATION_PARTICIPANT_CREATIONDATE, 
					CONVERSATION_PARTICIPANT_IDUSER, CONVERSATION_PARTICIPANT_IDFIL,
					CONVERSATION_PARTICIPANT_ACTIF) 
					VALUES 
				';
			$QueryParams = new ListDatabaseQueryParam();
			
			foreach ($List as $key => $Participant) {
				$query .= " (NOW(), :id_user_$key, :id_thread_$key, :is_active_$key), ";
				$QueryParams->Add(
					new DatabaseQueryParam(":id_user_$key", $Participant->id_user, PDO::PARAM_INT), 
					new DatabaseQueryParam(":id_thread_$key", $Participant->id_thread, PDO::PARAM_INT),
					new DatabaseQueryParam(":is_active_$key", $Participant->is_active, PDO::PARAM_BOOL),
				);
				
				$nb_inserts++;
			}
			
			if (str_ends_with2($query, ", "))
				$query = rtrim($query, ", ");

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
	
	
	public function Delete(ConversationParticipant ...$ArrayConversationParticipant): bool {
		throw new Exception('Not implemented yet');
	}
	
	public function EraseById(int $id): bool {
		throw new Exception('Not implemented yet');
	}

	#endregion
}


