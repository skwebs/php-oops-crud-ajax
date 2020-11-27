<?php
/**
* Enable error reporting
*/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
* Created Autoloader
*/
spl_autoload_register(function ($class) {
    $classes = __DIR__."/classes/".$class.".php";
    if (file_exists($classes)){
	    require $classes;
    }else{
	    exit( $classes." not found.");
    }
});

/**
* Session Started
*/
session_start();

//date_default_timezone_set('Asia/Kolkata');


/**
* created Crud class object
*/
$usr = new Crud ("ama");
$tblName = "users";


$data = [];
/**
* Check request type,
* if request type post then
*/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	extract($_POST);
	
	/**
	* This is registration section code
	*/
	if($action == "login_user"){
		
		/**
		* Change date format
		*/
		$date = date_create_from_format("d-m-Y", $dob);
		$dob = date_format($date,"Y-m-d"); 
		
		/**
		* password from dob
		*/
		$password = date_format($date,"dmY") ;
		
		//$reg_num = 1606342450;
		/**
		* 
		*/
		$conditions = array(
			"return_type" => "single",
			"where" => array(
				"reg_num" => $reg_num
			)
		);
		
		$userData = $usr->getRows($tblName, $conditions);
	//	var_dump($userData);exit;
		if(password_verify($password, $userData["password"])){
			//echo "User veified!";	
			//session_start();
			$_SESSION["isLoggedIn"]	= true ;	
			$_SESSION["reg_num"] = $reg_num;
			header("Location: home.html");
			exit ;
		}else{
			$login_err = 'User not veified!';
			echo "$login_err<script>alert('$login_err')</script>";			
		}
	}
} // post request
?>