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
* created crud object
*/
$usr = new Crud("ama");
$tblName = "users";

/**
* 
*/
$isImgSaved = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	extract($_POST);
	
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
		if($action == "add"){
			$date = date_create_from_format("d-m-Y", $dob);
			$dob = date_format($date,"Y/m/d"); 
			$password = date_format($date,"dmY") ;
			
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
			
			echo "\n".$statusMsg;
			
		}
	}
	
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
            /*if(!empty($users)){
             $count = 0; 
             foreach($users as $user){
              $count++;
              
              }
              }
              /*?>
              <tr>
                <td class="btn-group">
                  <a href="edit.php?id=<?php echo $user['id']; ?>" class="btn btn-outline-primary"><i class="fa fa-pencil"></i></a>
                  <a href="action.php?action_type=delete&id=<?php echo $user['id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Are you sure?');"><i class="fa fa-trash"></i></a>
                </td>
                <td><?php echo $count; ?></td>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['name']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['phone']; ?></td>
                <td><?php echo $user['created']; ?></td>
              </tr>
              <?php
               } 
              }else{ 
              ?>
              <tr>
                <td colspan="7">No user(s) found......</td>
              </tr>
              <?php
               } 
              ?>*/
              
	} // show data
	
	
} // post request