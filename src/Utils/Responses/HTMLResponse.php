<?php
namespace Utils\Responses;


/**
 * Display an php/html view, based on its path and the given data
 */
class HTMLResponse extends Response
{
	/**
	 * @var string the path of the view
	 */
	private string $path = '';

	/**
	 * @var array<string, mixed|iterable> the data used by the view
	 */
	private array $data = [];


	public function __construct(string $path, array $data = [])
	{
		$this->path = $path;
		$this->data = $data;
	}


	/**
	 * Unset all variables and display the template with all
	 * @return never
	 */
	public function Process(): void {
		extract($this->data);

		require_once $this->path;
		exit;
	}
}