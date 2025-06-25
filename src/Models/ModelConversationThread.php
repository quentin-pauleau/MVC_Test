<?php
namespace Models;

use Exception;
use PDO;

use Traits\Singleton;

use Models\Entities\ConversationThread;
use Models\EntityLists\ListConversationThread;

use Core\Database\DatabaseException;
use Core\Database\DatabaseQueryParam;
use Core\Database\ListDatabaseQueryParam;


/**
 * This class is the model for the {@see ConversationThread} database object
 * 
 * database object : {@see ConversationThread}
 * list : {@see ListConversationThread}
 */
final class ModelConversationThread extends Model
{
	private function __construct() {
		parent::__construct();
	}

	use Singleton;

	public function NewObject(array $data): ConversationThread {
		$ConversationThread = new ConversationThread();

		$ConversationThread->id ??= $data["CONVERSATION_FIL_ID"];

		if (isset($data["CONVERSATION_FIL_CREATIONDATE"])) {
			$ConversationThread->SetCreatedAtFromString($data["CONVERSATION_FIL_CREATIONDATE"]);
		}

		$ConversationThread->title ??= $data["CONVERSATION_FIL_TITRE"];
		$ConversationThread->is_active ??= $data["CONVERSATION_FIL_ACTIF"];

		return $ConversationThread;
	}

	
	public function NewList(array $data): ListConversationThread {
		$List = new ListConversationThread();

		foreach ($data as $ConversationThread) {
			$List->Add($this->NewObject($ConversationThread));
		}

		return $List;
	}

	#region Selection Queries

	/**
	 * Summary of GetById
	 * @param int $id
	 * @return ?ConversationThread
	 */
	public function GetById(int $id): ?ConversationThread {
		try
		{
			$data = $this->Database->GetOne(
				"SELECT * FROM conversation_fil WHERE CONVERSATION_FIL_ID = :id", 
				new ListDatabaseQueryParam(
					new DatabaseQueryParam(":id", $id, PDO::PARAM_INT)
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
	 * @return ListConversationThread
	 */
	public function GetAll() : ListConversationThread {
		try
		{
			return $this->NewList(
				$this->Database->GetList(
					"SELECT * FROM conversation_fil"
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
				"SELECT CONVERSATION_FIL_ID FROM conversation_fil WHERE CONVERSATION_FIL_ID = :id", 
				new ListDatabaseQueryParam(
					new DatabaseQueryParam(":id", $id, PDO::PARAM_INT)
				)
			);

			return $data !== [];
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
	 * @param \Models\Entities\ConversationThread[] $ArrayThread
	 * @return bool
	 */
	public function Insert(ConversationThread ...$ArrayThread): bool {
		if ($ArrayThread === [])
			return true;
		
		try
		{
			$nb_inserts = 0;
			
			foreach ($ArrayThread as $Thread) {
				$Thread->id = $this->Database->InsertOne(
					"INSERT INTO conversation_fil 
					(CONVERSATION_FIL_CREATIONDATE, CONVERSATION_FIL_TITRE, CONVERSATION_FIL_ACTIF) 
					VALUES 
					(NOW(), :title, :active)",
					new ListDatabaseQueryParam(
						new DatabaseQueryParam(":title", $Thread->title), 
						new DatabaseQueryParam(":active", $Thread->is_active),
					)
				);
				
				$nb_inserts++;
			}
			
			return $nb_inserts === count($ArrayThread);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}


	public function InsertList(ListConversationThread $List): bool {
		throw new Exception("Not implemented yet");
	}


	/**
	 * Update Conversation Thread
	 * @param \Models\Entities\ConversationThread[] $ConversationThreads
	 * @return bool
	 */
	public function Update(ConversationThread ...$ConversationThreads): bool {
		if ($ConversationThreads === [])
			return true;
		
		try
		{
			$query = '';
			$QueryParams = new ListDatabaseQueryParam();

			foreach ($ConversationThreads as $key => $ConversationThread) {
				$query .= "UPDATE conversation_fil SET 
				CONVERSATION_FIL_TITRE = :title_$key,
				CONVERSATION_FIL_ACTIF = :is_active_$key
				WHERE CONVERSATION_FIL_ID = :id_$key; ";
				
				$QueryParams->Add(
					new DatabaseQueryParam(":id_$key", $ConversationThread->id, PDO::PARAM_INT), 
					new DatabaseQueryParam(":title_$key", $ConversationThread->title, PDO::PARAM_STR), 
					new DatabaseQueryParam(":is_active_$key", $ConversationThread->is_active, PDO::PARAM_BOOL)
				);
			}

			$nb_updates = $this->Database->Update(
				$query,
				$QueryParams
			);

			return $nb_updates === count($ConversationThreads);
		}
		catch (DatabaseException $e)
		{
			throw $e;
		}
	}
	
	
	public function Delete(ConversationThread ...$ArrayConversationThread): bool {
		throw new Exception("Not implemented yet");
	}
	
	public function EraseById(int $id): bool {
		throw new Exception("Not implemented yet");
	}

	#endregion
}


