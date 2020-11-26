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

$isImgSaved = false;

/**
* Check request type,
* if request type post then
*/
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	extract($_POST);
	
	
	/**
	* This is registration section code
	*/
	if($action == "reg_usr"){
		
		/**
		* Change date format
		*/
		$date = date_create_from_format("d-m-Y", $dob);
		$dob = date_format($date,"Y-m-d"); 
		
		/**
		* Hashed password (dob)
		*/
		$password = date_format($date,"dmY") ;
		
		/**
		* First check user is new or already registered
		*/
		$conditions = array(
			"return_type" => "count",
			"where" => array(
				"first_name" => $first_name,
				 "last_name" => $last_name,
				 "father" => $father,
				 "dob" => $dob
			 )
		);
		
		$checkUsr = $usr->getRows($tblName, $conditions);
		if($checkUsr > 0){
	       /**
	       * if user already registered then 
	       */
	       echo "You are already registered.";
		} else {
			
			/**
			* set timestamp as registration number
			*/
			$reg_num = time();
			
			/**
			* Set directory to store image
			*/
			$img_dir = __DIR__."/user_img/";
			
			/**
			* Set image name
			*/
			$img_name = $reg_num."-".date("Ymd_His") . ".jpg";
			
			/**
			* If user is new then 
			* first upload image 
			*/
			foreach($_FILES as $file=>$arr){
			
			/**
			* created ResizeImg object
			*/
			$usrImg = new ResizeImg;
			
			/**
			* get image file name.
			*/
			$imgFile = $_FILES[$file];
				/**
				* give image source for resize.
				*/
				$usrImg->imgSource($imgFile);
				/**
				* resize image and save
				*/
				$res = $usrImg->saveImg($img_name,$img_dir);
				
				/**
				* check image saved or, not.
				*/
				if($res){
					/**
					* if image created and saved
					*/
					$isImgSaved = true;
				}else{
					/**
					* if image didn't create
					*/
					echo "Image didn't created";
				}
			}
			
			/**
			* If uploaded user image then,
			* insert user data in database
			*/
			if($isImgSaved){
				/**
				* create array of user data for inserting 
				*/
				$userData = array(
					 'reg_num' 		=> $reg_num,
					 'first_name' 	=> $_POST['first_name'],
					 'mid_name' 	=> $_POST['mid_name'],
					 'last_name' 	=> $_POST['last_name'],
					 'class' 		=> $_POST['class'],
					 'gender' 		=> $_POST['gender'],
					 'dob' 			=> $dob,
					 'mobile' 		=> $_POST['mobile'],
					 'father' 		=> $_POST['father'],
					 'present_address' => $_POST['present_address'],
					 'user_img' 	=> $img_name
				 );
				
				/**
				* process to insert data into database
				*/
				$insert = $usr->insert($tblName, $userData);
				
				/**
				* Get response message 
				*/
				$statusMsg = $insert?'User data has been inserted successfully.':'Some problem occurred, please try again.';
				echo "\n".$statusMsg;
			}
		}
	} // reg_usr


	if($action == "show_data"){
		$cond = array(
		"return_type"=>"single",
		//"order_by"=>"id",
		//"order_type"=>"DESC",
		"where" => array( "reg_num" => $reg_num )
		);
		$users = $usr->getRows('users', $cond);
        $json = json_encode($users);
        echo $json;     
	} // show data
	
	if($action == "check_login"){
		if(isset($_SESSION["isLoggedIn"]) && $_SESSION["isLoggedIn"] == true){
			echo json_encode($_SESSION["loggedUserData"]);
		}else{
		   
		}
	}
	
	
	////////////////////

} // post request