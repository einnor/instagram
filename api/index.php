<?php
include '../classes/inc.php';
require 'Slim/Slim.php';
$marray=array();

global $app;

$app= new Slim();
$app->get('/user/logout','logout');
$app->post('/user/login','login');

//users
$app->get('/users','getAllUsers');
$app->post('/users','createUser');

//posts
$app->get('/posts','getAllPosts');
$app->post('/posts','createPost');

$app->run();

function login(){$pdo = getConnection();
	global $app;
	$request = $app->request();
	$reqdata = json_decode($request->getBody());
	
	$ss="SELECT u.id,u.username,a.password FROM user u LEFT JOIN account a ON u.id=a.user_id WHERE u.email=? OR u.username=?";
	$stmt = $pdo->prepare($ss);$stmt->execute(array($reqdata->username,$reqdata->username));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($stmt->rowCount()>0){
		include_once '../classes/PasswordHash.php';
		$t_hasher = new PasswordHash(8, FALSE);	
		$check = $t_hasher->CheckPassword($reqdata->password, $row['password']);
		if ($check!=1){
			$marray['response']="fail";$marray['message']="passwords do not match";
			echo json_encode($marray);
			return false;
		}else{
			$marray['response']		="success";
			$marray['username']		=$row['username'];
			$marray['userid']		=$row['id'];
			
			$_SESSION['userid'] = NULL;
			$_SESSION['username'] = NULL;
			
			unset($_SESSION['userid']);
			unset($_SESSION['username']);
			
			$_SESSION['userid']		=$row['id'];
			$_SESSION['username']	=$row['username'];

			echo json_encode($marray);
			return false;
		}
	}else{
		$marray['response']="fail";$marray['message']="account does not exist";
		echo json_encode($marray);
		return false;	
	}
	return true;
}; //Login
function logout(){$pdo = getConnection();
	global $app;
	$request = $app->request();
	$reqdata = json_decode($request->getBody());
	$_SESSION['userid'] = NULL;
	$_SESSION['username'] = NULL;
	
	unset($_SESSION['userid']);
	unset($_SESSION['username']);
}


function getAllUsers(){
	$pdo = getConnection();
	$marray = array();
	try{
		$ss="SELECT * FROM user";
		$stmnt=$pdo->prepare($ss);$stmnt->execute();$resp=array();
		while($row = $stmnt->fetch(PDO::FETCH_OBJ)) { $resp[]=$row; }
		$marray['response']=$resp;
	}catch(PDOExpetion $e){$marray['error']=$e->getMessage();}
	echo json_encode($marray);
	return false;
}

function createUser(){
	$pdo = getConnection();$marray=array();
	global $app; $request = $app->request();
	$reqdata = json_decode($request->getBody());
	try{
		include_once '../classes/PasswordHash.php';
		$t_hasher = new PasswordHash(8, FALSE);
		$mypass = $t_hasher->HashPassword($reqdata->password);
		$ss = "INSERT INTO user(username,email,phone,first_name,second_name,avatar,dob) VALUES(?,?,?,?,?,?,?)";
		$stmt = $pdo->prepare($ss);$stmt->execute(array($reqdata->username,$reqdata->email,$reqdata->phone,$reqdata->first_name,$reqdata->second_name,$reqdata->avatar,$reqdata->dob));
		$userid = $pdo->lastInsertId();
		$ss1 = "INSERT INTO account(user_id,password) VALUES(?,?)";
		$stmt1=$pdo->prepare($ss1);$stmt1->execute(array($userid,$mypass));
		
		$marray['username'] = $reqdata->username;
		$marray['userid'] = $userid;
		
		$_SESSION['userid'] = NULL;
		$_SESSION['username'] = NULL;
		
		unset($_SESSION['userid']);
		unset($_SESSION['username']);
		
		$_SESSION['userid'] = $userid;
		$_SESSION['username']	=$reqdata->username;

		$marray['response']="success";
	}catch(PDOException $e){$marray['error']=$e->getMessage();$marray['response']="fail";}
	echo json_encode($marray);
	return true;
}

function createPost(){
	$pdo = getConnection();$marray=array();
	global $app; $request = $app->request();
	$reqdata = json_decode($request->getBody());
	try{
		$ss = "INSERT INTO post(user_id,name,caption) VALUES(?,?,?)";
		$stmt = $pdo->prepare($ss);$stmt->execute(array(getuserid(),$reqdata->name,$reqdata->caption));
		$postid = $pdo->lastInsertId();

		$ss = "INSERT INTO post_details(post_id,size,extension,orientation,resolution,dimensions) VALUES(?,?,?,?,?,?)";
		$stmt = $pdo->prepare($ss);$stmt->execute(array($postid,$reqdata->size,$reqdata->extension,$reqdata->orientation,$reqdata->resolution,$reqdata->dimensions));

		$marray['response']="success";
	}catch(PDOException $e){$marray['error']=$e->getMessage();$marray['response']="fail";}
	echo json_encode($marray);
	return true;
}

?>