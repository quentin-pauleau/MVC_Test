<?php
namespace Module\Console;


class Console
{



	public function __construct()
	{

	}


	public function write($text): void
	{
		echo $text;
	}

	public function writeln($text): void
	{
		echo $text . PHP_EOL;
	}


	public function read(): string
	{
		return trim(fgets(STDIN));
	}
}