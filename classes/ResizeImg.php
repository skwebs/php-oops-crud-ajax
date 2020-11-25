<?php
/**
*
* @Author	: Satish Kumar Sharma
* @Website	: https://skwebs.github.io
*
*/
class ResizeImg{
	
	/**
	* The Temporary Name of the image
	*
	* @var string
	*/
	private $imgTmpName = ""; 		// image tmp_name
	
	/**
	* The Width of the Source Image
	*
	* @var int
	*/
	private $srcWidth = ""; 		// image width
	
	/**
	* The Height of the Source Image
	*
	* @var int
	*/
	private $srcHeight = "";		// image height
	
	/**
	* The MIME Content-type of the image
	*
	* @var string
	*/
	private $mimeType = "";			// image Mime-Type
	
	/**
	* The New Image Width
	*
	* @var int
	*/
	private $newWidth = "";			// image new width to resize
	
	/**
	* The New Image Height
	*
	* @var int
	*/
	private $newHeight = "";		// image new height to resize
	
	/**
	* The New image extension
	*
	* @var int
	*/
	//private $newImgExt = "";		// image new height to resize
	
	/**
	* The function imgSource() take image array parameter
	* to get all image properties
	* 
	* @param array		$imgSource	Image resources     To
	*/
	public function imgSource($imgFile){
		
		/**
		* Store image temporary name
		*/
		$this->imgTmpName = $imgFile["tmp_name"];
		
	   /**
		* Extract image size and type by php 
		* list function and getimagesize function
		*/
		list($img_src_width, $img_src_height, $type) = getimagesize($imgFile["tmp_name"]);
		
	   /**
		* Store image width
		*/
		$this->srcWidth = $img_src_width;
		
		/**
		* Store image height
		*/
		$this->srcHeight = $img_src_height;
		
		/**
		* Store image mime type
		*/
		$this->mimeType = $type;
		
		/**
		* Set source image width as new image default width
		*/
		$this->newWidth = $img_src_width;
		
		/**
		* Set source image height as new image default height
		*/
		$this->newHeight = $img_src_height;
	}
	
	/**
	 * The Setter function setSize() 
	 * use to set new image width and height 
	 * in given ratio.
	 *
	 * It's @param value should be 0 to 1
	 *
	 * @param int	$ratio	Ratio to set width and height
	 * 
	 * Use anyone function at once 
	 * (a) setSize() or, 
	 * (b) setWidth() or, 
	 * (c) setHeight()
	 */
	public function setSize($ratio){
		
		/**
		* Set new image width 
		*/
		$this->newWidth = $img_src_width * $ratio;
		
		/**
		* Set new image height
		*/
		$this->newHeight = $img_src_height * $ratioa;
	}
	
   /**
	* Setter function setWidth() set width of new image 
	* And also set new image height in width ratio
	* 
	* @param int		$width	New Image Width
	*
	* Use anyone function at once 
	* (a) setSize() or, 
	* (b) setWidth() or, 
	* (c) setHeight()
	*/
	public function setWidth($width){
		/**
		* Set width value that given by user
		*/
		$this->newWidth = $width;
		/**
		* Set height value automatically 
		* in ratio of user given width 
		*/
		$this->newHeight = ($width * $img_src_height) / $img_src_width;
	}
	
   /**
	* Setter function setHeight() set height of new image 
	* And also set new image width in height ratio
	* 
	* @param int		$height		New Image Height
	*
	* Use anyone function at once 
	* (a) setSize() or, 
	* (b) setWidth() or, 
	* (c) setHeight()
	*/
	public function setHeight($height){
		
		/**
		* Set height value that given by user
		*/
		$this->newHeight = $height;
		
		/**
		* Set width value automatically 
		* in ratio of user given height 
		*/
		$this->newWidth = ($height * $img_src_width ) / $img_src_height;
	}
	
	/**
	* Call function saveImg() to save image
	* 
	* @param string		$newImgName		new image name
	* @param string		$newImgDir		image directory to save it
	* @param int		$newImgQlity	new image quality 0 to 100
	* 
	* Value of @param $newImgQlity should be 0 to 100
	* Default value of @param $newImgQlity is 100
	*/
	public function saveImg($newImgName,$newImgDir, $newImgQlity=100){
		
		/**
		* Check new image directory is exists
		*/
		if (!file_exists($newImgDir)){
			/**
			* Create new image directory if not exists
			*/
			mkdir($newImgDir, 0777, true);
		}
		
		/**
		* The imagecreatetruecolor() function is an inbuilt function in PHP which is used to 
		*
		* Create a new true color image. 
		* This function returns a blank image of the given size.
		* 
		* Syntax:  resource imagecreatetruecolor ( int $width , int $height ) 
		*/
		$new_img_layout = imagecreatetruecolor($this->newWidth, $this->newHeight);

		/**
		* For JPEG 
		* The imagecreatefromjpeg() function is an inbuilt function in PHP which is used to 
		* 
		* create a new image from JPEG file or URL. 
		*/
		switch ($this->mimeType) {
			case 1 : //gif
			$source = imagecreatefromgif($this->imgTmpName);
			break;
			case 2 : //jpeg
			$source = imagecreatefromjpeg($this->imgTmpName);
			break;
			case 3 : //png
			$source = imagecreatefrompng($this->imgTmpName);
			break;
			case 6 : //bmp
			$source = imagecreatefrombmp($this->imgTmpName);
			break;
		}
		
		/**
		* The imagecopyresized() function is an inbuilt function in PHP which is used to 
		* 
		* copy a rectangular portion of one image to another image.
		* 
		* Syntax: bool imagecopyresized ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
		*/
		imagecopyresized($new_img_layout, $source, 0, 0, 0, 0, $this->newWidth, $this->newHeight, $this->srcWidth, $this->srcHeight);
	    
	    /**
	    * for JPEG 
	    * The imagejpeg() function is an inbuilt function in PHP 
	    * which is used to display image to browser or file. 
	    * The main use of this function is to 
	    * 
	    * view an image in the browser, 
	    * Convert/Save any other image type to JPEG and altering the quality of the image.
	    * 
	    * Syntax: bool imagejpeg( resource $image, int $to, int $quality )
	    */
	    switch ($this->mimeType) {
		    case 1 : //gif
		    $outputImg = imagegif($new_img_layout, $newImgDir.$newImgName, $newImgQlity);
		    break;
		    case 2 : //jpeg
		    $outputImg = imagejpeg($new_img_layout, $newImgDir.$newImgName, $newImgQlity);
		    break;
		    case 3 : //png
		    $outputImg = imagepng($new_img_layout, $newImgDir.$newImgName, $newImgQlity);
		    break;
		    case 6 : //bmp
		    $outputImg = imagebmp($new_img_layout, $newImgDir.$newImgName, $newImgQlity);
		    break;
	    }
	    /**
	    * Check image created or not
	    */
	    if($outputImg) {
		    /**
		    * If image created and saved then destroy the image and return true
		    */
		    imagedestroy($new_img_layout);
		    return true;
		} else {
			/**
			* If image didn't create and save then return false.
			*/
			return false;
		};
	} // saveImg() end here

}

