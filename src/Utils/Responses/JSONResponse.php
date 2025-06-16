<?php
namespace Utils\Responses;

use JsonSerializable;

/**
 * Display json data
 */
class JSONResponse extends Response
{
	protected $data;

	public function __construct($data)
	{
		$this->data = $data;
	}


	public function Process(): void
	{
		header('Content-Type: application/json;');
		echo json_encode($this->data);
		exit;
	}
}