<?php
namespace Core\Responses;

/**
 * Display a text from a string
 * 
 * the text is display as html if {@see self::is_html} is set to true
 */
class StringResponse extends Response
{
	/**
	 * 
	 * @var string
	 */
	private string $text = '';

	/**
	 * @var bool
	 */
	private bool $is_html = true;


	public function __construct(string $text, bool $is_html = true)
	{
		$this->text = $text;
		$this->is_html = $is_html;
	}


	/**
	 * Unset all variables and display the template with all
	 * @return never
	 */
	public function Process(): void {
		if ($this->is_html) {
			header('Content-Type: text/html; charset=utf-8');
			$this->text = htmlspecialchars($this->text);
		}
		else {
			header('Content-Type: text/plain; charset=utf-8');
		}
		
		echo $this->text;
		exit;
	}
}