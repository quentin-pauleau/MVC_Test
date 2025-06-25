<?php
namespace Core\Requests;

use Traits\Singleton;
use Core\Session\Session;


/**
 * An object representation of the request made by the user
 * 
 * {@see Request::$Query} for an object representation of $_GET
 * {@see Request::$Data} for an object representation of $_POST
 * {@see Request::$Server} for an object representation of $_SERVER
 * {@see Request::$Cookie} for an object representation of $_COOKIE
 * {@see Request::$Files} for an object representation of $_FILES
 * 
 * the session is accessible through the {@see Session} class
 * 
 * (Singleton) call {@see Request::GetRequest()} to get the instance of the request
 */
class Request
{
	use Singleton {
		GetInstance as public GetRequest;
	}

	private string $method;
	private string $uri;


	/**
	 * Data from the query (Query parameters)
	 * @var RequestData
	 */
	public RequestData $Query;

	/**
	 * {@see Request::$Query} alias
	 * @var RequestData
	 */
	public RequestData $Get;


	/**
	 * Data from a Post
	 * @var RequestData
	 */
	public RequestData $Data;

	
	/**
	 * {@see Request::$Query} alias
	 * @var RequestData
	 */
	public RequestData $Post;

	/**
	 * Data from $_SERVER
	 * @var RequestServer
	 */
	public RequestServer $Server;

	/**
	 * Data from $_COOKIE
	 * @var RequestCookies
	 */
	public RequestCookies $Cookie;

	/**
	 * Data from $_FILES
	 * @var RequestFiles
	 */
	public RequestFiles $Files;


	public function __construct() {
		//* instanciate the request data holder
		$this->Query = new RequestData($_GET);
		$this->Data = new RequestData($_POST);
		$this->Server = new RequestServer;
		$this->Cookie = new RequestCookies;
		$this->Files = new RequestFiles;
		
		//* create the alias
		$this->Get = $this->Query;
		$this->Post = $this->Data;

		//* set general infos
		$this->method = $_SERVER['REQUEST_METHOD'];
		$this->uri = $_SERVER['REQUEST_URI'];
	}


	public function GetMethod(): string {
		return $this->method;
	}


	public function GetUri(): string {
		return $this->uri;
	}


	public function GetRoute(): string {
		return 'controller='.$this->GetRouteController().'&action='.$this->GetRouteAction();
	}

	public function GetRouteController(): string {
		return $this->Query->Get('controller');
	}

	public function GetRouteAction(): string {
		return $this->Query->Get('action');
	}
}