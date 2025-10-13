<?php
// Set the needed settings
ini_set('max_execution_time', 300);
ini_set('upload_max_filesize', '20M');
ini_set('post_max_size', '25M');
ini_set('max_input_time', 300);

// Set the error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Require the needed files
require_once "src/Core/MoreFunctions.php";

spl_autoload_register(function ($class): void {
	$file = __DIR__ .'/src/'.str_replace('\\', '/', $class) . '.php';
	if (file_exists($file))
		require_once $file;
});

use Modules\Eclipse\Core;

// Initialise the app
Core::Init();

// Start the app
Core::StartApp();
