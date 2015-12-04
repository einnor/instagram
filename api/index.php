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

//posts & likes and comments
$app->get('/posts','getAllPosts');
$app->get('/posts/:id','getPost');
$app->post('/posts/like/:id','likeAPost');
$app->post('/posts/comment/:id','AddAComment');
$app->get('/posts/comments/:id','getComments');
$app->post('/posts','createPost');

$app->run();

function login(){$pdo = getConnection();
	global $app;
	$request = $app->request();
	$reqdata = json_decode($request->getBody());
	
	$ss="SELECT u.id,u.first_name,u.second_name,u.username,a.password FROM user u LEFT JOIN account a ON u.id=a.user_id WHERE u.email=? OR u.username=?";
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
			$marray['name']		=$row['first_name'].' '.$row['second_name'];
			$marray['userid']		=$row['id'];
			
			$_SESSION['userid'] = NULL;
			$_SESSION['name'] = NULL;
			
			unset($_SESSION['userid']);
			unset($_SESSION['username']);
			unset($_SESSION['name']);
			
			$_SESSION['userid']		=$row['id'];
			$_SESSION['username']	=$row['username'];
			$_SESSION['name']	=$row['first_name'].' '.$row['second_name'];

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
	unset($_SESSION['name']);
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
		$ss = "INSERT INTO user(username,email,phone,first_name,second_name,avatar,dob,description) VALUES(?,?,?,?,?,?,?,?)";
		$stmt = $pdo->prepare($ss);$stmt->execute(array($reqdata->username,$reqdata->email,$reqdata->phone,$reqdata->first_name,$reqdata->second_name,$reqdata->avatar,$reqdata->dob,$reqdata->description));
		$userid = $pdo->lastInsertId();
		$ss1 = "INSERT INTO account(user_id,password) VALUES(?,?)";
		$stmt1=$pdo->prepare($ss1);$stmt1->execute(array($userid,$mypass));
		
		$marray['username'] = $reqdata->username;
		$marray['name'] = $reqdata->first_name.' '.$reqdata->second_name;
		$marray['userid'] = $userid;
		
		$_SESSION['userid'] = NULL;
		$_SESSION['username'] = NULL;
		$_SESSION['name'] = NULL;
		
		unset($_SESSION['userid']);
		unset($_SESSION['username']);
		unset($_SESSION['name']);
		
		$_SESSION['userid'] = $userid;
		$_SESSION['username']	=$reqdata->username;
		$_SESSION['name']	=$reqdata->first_name.' '.$reqdata->second_name;

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

function likeAPost($id){
	$pdo = getConnection();$marray=array();
	global $app; $request = $app->request();
	$reqdata = json_decode($request->getBody());
	try{
		$ss="SELECT user_id FROM instagram.like WHERE post_id=? AND user_id=?";
		$stmnt=$pdo->prepare($ss);$stmnt->execute(array($id,getuserid()));$resp=array();
		$row = $stmnt->fetch(PDO::FETCH_ASSOC);
		if ($stmnt->rowCount()>0){//User has already liked this image
			$ss = "DELETE FROM instagram.like WHERE user_id=? AND post_id=?";
			$stmt = $pdo->prepare($ss);$stmt->execute(array(getuserid(),$reqdata->post_id));

			$ss = "UPDATE post SET numberoflikes=numberoflikes-1 WHERE id=?";
			$stmt = $pdo->prepare($ss);$stmt->execute(array($reqdata->post_id));
		}else{
			$ss = "INSERT INTO instagram.like(user_id,post_id) VALUES(?,?)";
			$stmt = $pdo->prepare($ss);$stmt->execute(array(getuserid(),$reqdata->post_id));

			$ss = "UPDATE post SET numberoflikes=numberoflikes+1 WHERE id=?";
			$stmt = $pdo->prepare($ss);$stmt->execute(array($reqdata->post_id));
		}	

		$marray['response']="success";
	}catch(PDOException $e){$marray['error']=$e->getMessage();$marray['response']="fail";}
	echo json_encode($marray);
	return true;
}

function AddAComment($id){
	$pdo = getConnection();$marray=array();
	global $app; $request = $app->request();
	$reqdata = json_decode($request->getBody());
	try{
		$ss = "INSERT INTO comment(user_id,post_id,body) VALUES(?,?,?)";
		$stmt = $pdo->prepare($ss);$stmt->execute(array(getuserid(),$id,$reqdata->comment));

		$ss = "UPDATE post SET numberofcomments=numberofcomments+1 WHERE id=?";
		$stmt = $pdo->prepare($ss);$stmt->execute(array($id));	

		$marray['response']="success";
	}catch(PDOException $e){$marray['error']=$e->getMessage();$marray['response']="fail";}
	echo json_encode($marray);
	return true;
}


function getAllPosts(){
	$pdo = getConnection();
	$marray = array();
	try{
		$ss="SELECT p.id,p.name,p.caption,p.thedate AS timeposted,p.numberofcomments,p.numberoflikes,u.username AS owner,(SELECT IF(user_id=?,'true','false') FROM instagram.like WHERE post_id=p.id) AS hasliked FROM post p LEFT JOIN user u ON p.user_id=u.id ORDER BY p.id DESC";
		$stmnt=$pdo->prepare($ss);$stmnt->execute(array(getuserid()));$resp=array();
		while($row = $stmnt->fetch(PDO::FETCH_OBJ)) { $resp[]=$row; }
		$marray['response']=$resp;
	}catch(PDOExpetion $e){$marray['error']=$e->getMessage();}
	echo json_encode($marray);
	return false;
}

function getPost($id){
	$pdo = getConnection();
	$marray = array();
	try{
		$ss="SELECT p.id,p.name,p.caption,p.thedate AS timeposted,p.numberofcomments,p.numberoflikes,u.username AS owner,(SELECT IF(user_id=?,'true','false') FROM instagram.like WHERE post_id=p.id) AS hasliked FROM post p LEFT JOIN user u ON p.user_id=u.id WHERE p.id=? ORDER BY p.id DESC";
		$stmnt=$pdo->prepare($ss);$stmnt->execute(array(getuserid(),$id));$resp=array();
		while($row = $stmnt->fetch(PDO::FETCH_OBJ)) { $resp[]=$row; }
		$marray['response']=$resp;
	}catch(PDOExpetion $e){$marray['error']=$e->getMessage();}
	echo json_encode($marray);
	return false;
}

function getComments($id){
	$pdo = getConnection();
	$marray = array();
	try{
		$ss="SELECT c.body,c.thedate AS timeposted,u.username AS owner FROM comment c LEFT JOIN user u ON c.user_id=u.id WHERE c.post_id=? ORDER BY c.id DESC";
		$stmnt=$pdo->prepare($ss);$stmnt->execute(array($id));$resp=array();
		while($row = $stmnt->fetch(PDO::FETCH_OBJ)) { $resp[]=$row; }
		$marray['response']=$resp;
	}catch(PDOExpetion $e){$marray['error']=$e->getMessage();}
	echo json_encode($marray);
	return false;
}
?>