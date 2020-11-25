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
				echo "\nImage created! \n$img_dir$img_name";
			}else{
				echo "Image didn't created";
			}
		}
	}
?>