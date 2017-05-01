<?php
session_start(); //start session so we can store user info
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/php/pdo_mysql_helper.php"); //for mysql helper class
$DBC =  new pdo_mysql_helper($_SERVER['DOCUMENT_ROOT'] . "/inc/php/credentials.php");

$user = $_POST["username"];
$password = md5($_POST["password"]);

$select = "SELECT * FROM  user_info_no_fb WHERE username=? AND password=?";
$DBC->query($select, array($user,$password));

if(count($DBC->fetch_all_assoc()) > 0){
	echo json_encode(array("success"=>true));
}else{
	echo json_encode(array("success"=>false));
}


?>