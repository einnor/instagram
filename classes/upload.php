<?php	
	$location = '../images/avatar/';//SPECIFY LOCATION!!!!!!!!!!!!!!!!
	
	if(isset($_FILES["avatarfile"])){
		$ret 				= array();
		$error 				=$_FILES["avatarfile"]["error"];
		$fileName 			= $_FILES["avatarfile"]["name"];
		$U_path=$location;
	
		if (!file_exists($U_path)) { @mkdir($U_path); }chmod( $U_path,0777);
		$uploadhere			=$U_path.basename( $_FILES["avatarfile"]["name"] );
		$filename			= basename( $_FILES["avatarfile"]["name"] );
		if (file_exists($uploadhere)) { $uploadhere=$U_path.date('ydmhis').$filename; }
		//UPLOAD IMAGE HERE - TO DELETE LATER
		$ret['name']= $uploadhere;
		if (!move_uploaded_file($_FILES["avatarfile"]["tmp_name"], $uploadhere)) {
			die(json_encode(array("jquery-upload-file-error"=>'Error Uploading the file. Please try again later')));
		}
		echo json_encode($ret);
	}
?>