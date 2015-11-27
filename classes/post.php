<?php
include '../classes/inc.php';
if(isset($_FILES["avatarfile"])){
	$ret = array();
	$error =$_FILES["avatarfile"]["error"];
	//You need to handle  both cases
	//If Any browser does not support serializing of multiple files using FormData() 
 	 	$fileName = $_FILES["avatarfile"]["name"];
 		//INNITIAL CHECK UP
		$validExtensions = array('.jpg', '.jpeg', '.gif', '.png','.JPG', '.JPG', '.GIF', '.PNG');
		$fileExtension = strrchr($_FILES['avatarfile']['name'], ".");// get extension of the uploaded file
		if (!in_array($fileExtension, $validExtensions)) {die(json_encode(array("jquery-upload-file-error"=>'Invalid file type')));} // check if file Extension is on the list of allowed ones
		$ret['imagetype'] =$fileExtension;
		$ImageSize 		= $_FILES['avatarfile']['size']; // Obtain original image size
		$ret['size'] 	=$ImageSize;
		//if ($ImageSize>10000000){die(json_encode(array("jquery-upload-file-error"=>'You require an image with a size of NOT more that 10MB. Your Image is Larger');}
		
		$imagename			= basename( $_FILES["avatarfile"]["name"] );;
		$clean 				= '../images/avatars/'.$imagename;
		
		//UPLOAD IMAGE HERE - TO DELETE LATER
		if (!move_uploaded_file($_FILES["avatarfile"]["tmp_name"], $clean)) {
			die(json_encode(array("jquery-upload-file-error"=>'Error Uploading the image. Please try again later')));
		}else{
			$editimage			=$clean;
			
			//GET ORIGINAL FROM SETTINGS
			$original=new Imagick($editimage);
			//CHECK RESOLUTION AND SIZE
			$myarr = $original->getImageResolution(); // Array ( [x] => 72 [y] => 72 )
			$mres=$myarr['x']; $ret['resolution'] 	=$mres;
			//if($mres<200){ die(json_encode(array("jquery-upload-file-error"=>'You require an image with a DPI of over 200px. Your Image has '. $mres))); }
			
			$oriental=3;
			list($width, $height) = getimagesize($editimage);
			$ret['dimensions']= $width.'X'.$height;
			if ($width > $height) { $oriental=1;  $ret['orientation']='Landscape';} else {$oriental=2;}; $ret['orientation'] ="Portrait";						
			
			$filename		=$out_large;
			$size			=$ImageSize;
			$dimensions		= $width.'X'.$height;
			$imagetype		=$fileExtension;
			$resolution 	=$myarr['x'];
			$orientation	=$oriental;

			$ret['filename'] 	=$filename;
			$ret['name'] 		=$imagename;
			$ret['name'] 		=$imagename;
		}
		echo json_encode($ret);
	}
?>