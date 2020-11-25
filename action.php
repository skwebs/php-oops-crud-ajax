<?php
spl_autoload_register(function ($class) {
    $classes = __DIR__."/classes/".$class.".php";
    if (file_exists($classes)){
	    require $classes;
    }else{
	    exit( $classes." not found.");
    }
});

session_start();

date_default_timezone_set('Asia/Kolkata');

$reg_num = time();
$img_dir = __DIR__."/user_img/";
$img_name = $reg_num."-".date("Ymd_His") . ".jpg";
/**
* created ResizeImg object
*/
$usrImg = new ResizeImg;

/**
* 
*/
$isImgSaved = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	foreach($_FILES as $file=>$arr){
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
			$isImgSaved = true;
		//	echo "\nImage created! \n$img_dir$img_name";
		}else{
			echo "Image didn't created";
		}
	}
	
	if($isImgSaved){
		extract($_POST);
		
		$date = date_create_from_format("d-m-Y", $dob);
		$dob = date_format($date,"Y/m/d"); 
		$password = date_format($date,"dmY") ;
		
		/**
		* created crud object
		*/
		$usr = new Crud("ama");
		$tblName = "users";
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
		
		$insert = $usr->insert($tblName, $userData);
		$statusMsg = $insert?'User data has been inserted successfully.':'Some problem occurred, please try again.';
		
		echo "\n\n".$statusMsg;
		
	}
}
