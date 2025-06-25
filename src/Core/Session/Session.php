<?php
namespace Core\Session;

use Traits\Singleton;

/**
 * Object representation of $_SESSION
 * 
 * (Singleton) {@see Session::GetSession()} to get the instance of the session
 */
final class Session
{
	use Singleton {
		GetInstance as public GetSession;
	}

	/**
	 * @var string[]
	 */
	private static array $CollectionUnSettingList;

	/**
	 * Summary of User
	 * @var SessionUser
	 */
	public SessionUser $User;

	/**
	 * 
	 * @var SessionDataCollection
	 */
	public SessionDataCollection $Data;

	/**
	 * 
	 * @var SessionErrorCollection
	 */
	public SessionErrorCollection $Error;

	/**
	 * 
	 * @var SessionDataCollection
	 */
	public SessionDataCollection $Debug;

	private function __construct() {
		$this->Start();
		
		$this->User = SessionUser::GetInstance();

		//* persistant data handleing
		$_SESSION['CollectionUnSettingList'] = [];
		self::$CollectionUnSettingList = $_SESSION['CollectionUnSettingList'];

		foreach (self::$CollectionUnSettingList as $Collection) {
			$this->UnsetCollection($Collection);
		}
		
		$this->Data = new SessionDataCollection('default');
		$this->Debug = new SessionDataCollection('debug');

		//* persistant error handleing
		$this->Data = new SessionErrorCollection('default');
		
		self::$CollectionUnSettingList = self::GetCollectionNames();
	}

	/**
	 * Start the session if it's not already started
	 * @return void
	 */
	public function Start(): bool {
		return session_start();
	}


	#region Session Data Collections

	public function NewDataCollection(string $name): SessionDataCollection {
		return new SessionDataCollection($name);
	}
	
	public function GetCollectionNames(): array {
		return array_keys($_SESSION[SessionDataCollection::ARRAY_VALUE]);
	}

	final public function GetCollectionUnSettingList(): array {
		return self::$CollectionUnSettingList;
	}

	public function KeepCollection(string $name): bool {
		$key = array_search($name, self::$CollectionUnSettingList, true);
		
		if ($key === false) {
			return true;
		}

		unset(self::$UnSettingList[$key]);

		return true;
	}

	public function UnsetCollection(string $name): bool {
		unset(self::$UnSettingList[$name]);
		return true;
	}

	#endregion Session Data Collection
}