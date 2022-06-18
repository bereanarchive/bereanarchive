<?php

// Display errors if debug flag is present.
if (isset($_GET['debug'])) {
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}

// Convert all errors to exceptions.
// This way warnings, notices, etc. will be caught in try/catch.
set_error_handler(function ($level, $message, $file, $line) {

	// Ignore errors not enabled for reporting.
	if (!($level & error_reporting()))
		return;

	// https://www.php.net/manual/en/errorfunc.constants.php#119731
	$errorTypes = array_flip(array_slice(get_defined_constants(true)['Core'], 1, 15, true));
	throw new ErrorException($errorTypes[$level].' '.$message, 0, $level, $file, $line);
});

// Misc initialization
set_include_path( __DIR__);
chdir(__DIR__);
date_default_timezone_set('UTC');


// Call session_start() and modify its cookie to be more secure.
// Session start must occur after includes, so that objects within the session can be properly rebuilt.
// HttpOnly prevents javascript from accessing this cookie.  So XSS-injected scripts can't steal the session.
//     https://www.owasp.org/index.php/HttpOnly
// SameSite=Strict prevents this cookie from being set when a user clicks a link or submits a form on an external page.
//     So if a user is on someothersite.com and clicks a link to bolt.com/admin/deleteSomething.php, they will be
//     Forced to login agiain.
$newSession = !isset($_COOKIE['PHPSESSID']);
session_start();
if ($newSession)
	header('Set-Cookie: PHPSESSID=' . session_id() . '; path=/; HttpOnly; domain=' .$_SERVER['SERVER_NAME']. '; SameSite=Strict');
