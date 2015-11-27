<?php
if (!isset($_SESSION)) {
	session_start();
	if (!isset($_SESSION['userid']) ){$_SESSION['userid']='-1';}
	if (!isset($_SESSION['username']) ){$_SESSION['username']='GUEST';}
}
$userid		= $_SESSION['userid']; 
$username 	= $_SESSION['username'];

function getConnection() {
	$dbhost="localhost";
	$dbuser="root";
	$dbpass="!_PASSword123";
	$dbname="instagram";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}
function getusername(){
	return $_SESSION['username'];
}
function getuserid(){
	return $_SESSION['userid'];
}
?>