<?php
include '../classes/inc.php';
if(isset($_FILES["postfile"])){
	$ret = array();
	$error =$_FILES["postfile"]["error"];
	//You need to handle  both cases
	//If Any browser does not support serializing of multiple files using FormData() 
 	 	$fileName = $_FILES["postfile"]["name"];
 		//INNITIAL CHECK UP
		$validExtensions = array('.jpg', '.jpeg', '.gif', '.png','.JPG', '.JPG', '.GIF', '.PNG','.mp4','.ogg','.wma','.avi','.mkv','.ogv','.gif','.wmv','.3gp','.mpg','.mpeg','.m2v');
		$fileExtension = strrchr($_FILES['postfile']['name'], ".");// get extension of the uploaded file
		if (!in_array($fileExtension, $validExtensions)) {die(json_encode(array("jquery-upload-file-error"=>'Invalid file type')));} // check if file Extension is on the list of allowed ones
		$ret['imagetype'] =$fileExtension;
		$ImageSize 		= $_FILES['postfile']['size']; // Obtain original image size
		$ret['size'] 	= $ImageSize;
		if ($ImageSize>10000000){die(json_encode(array("jquery-upload-file-error"=>'You require an image with a size of NOT more that 10MB. Your Image is Larger')));}
		
		$imagename			= basename( $_FILES["postfile"]["name"] );;
		$uploadhere			= "imageholder/".$imagename;
		$uploadfolder 		= '../images/posts/'.getuserid().'/';
		//$clean				= $uploadfolder."/". md5($imagename)."/";
		if (!file_exists( $uploadfolder )) { @mkdir( $uploadfolder ); }chmod( $uploadfolder,0777);
		//if (!file_exists( $clean )) { @mkdir( $clean ); }chmod( $clean,0777);
		if (file_exists($uploadfolder.$imagename)) {  $imagename = date('ydmhis').$imagename; }
		$uploadfolder		= $uploadfolder.$imagename;
		
		if (!move_uploaded_file($_FILES["postfile"]["tmp_name"], $uploadfolder)) {
			die(json_encode(array("jquery-upload-file-error"=>'Error Uploading the image. Please try again later')));
		}else{
			$imageproperties = $uploadfolder;
			$original = new Imagick($imageproperties);
			//CHECK RESOLUTION AND SIZE
			$myarr = $original->getImageResolution(); // Array ( [x] => 72 [y] => 72 )
			$mres = $myarr['x']; $ret['resolution'] = $mres;
			//if($mres<200){ die(json_encode(array("jquery-upload-file-error"=>'You require an image with a DPI of over 200px. Your Image has '. $mres))); }
			
			$oriental=3;
			list($width, $height) = getimagesize($imageproperties);
			$ret['dimensions']= $width.'X'.$height;
			if ($width > $height) { $oriental=1;  $ret['orientation']='Landscape';} else {$oriental=2;}; $ret['orientation'] ="Portrait";
			if($width<200 && $heigh<200){ die(json_encode(array("jquery-upload-file-error"=>'You require an image with a size of more that 500px BY 500px. Your Image is smaller'))); }
			
			//if($height<500){ die(json_encode(array("jquery-upload-file-error"=>'You require an image with a size of more that 500px BY 500px. Your Image is smaller'))); }		
			
			$filename		=$uploadfolder;
			$size			=$ImageSize;
			$dimensions		= $width.'X'.$height;
			$imagetype		=$fileExtension;
			$resolution 	=$myarr['x'];
			$orientation	=$oriental;
			
			$ret['filename'] 	=$filename;
			$ret['name'] 		=$imagename;			
		}
		echo json_encode($ret);
	}
?>