<?php
include '../classes/inc.php';
require 'Slim/Slim.php';
$marray=array();

global $app;

$app= new Slim();
$app->get('/users','getAllUsers');
$app->post('/users','createUser');

$app->run();

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

?>