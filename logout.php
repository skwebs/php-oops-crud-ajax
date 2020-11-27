<?php
/**
* Enable error reporting
*/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
* Session Started
*/
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	extract($_POST);
}else
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	extract($_GET);
}
	
	/**
	* This is registration section code
	*/
	if($action == "logout_user"){
		$_SESSION = array();
		session_destroy();
		header("Location: login.html");
		exit ;
	}



//} // post request
?>
