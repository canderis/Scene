<?php
session_start(); //start session so we can store user info
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/php/pdo_mysql_helper.php"); //for mysql helper class

$DBC =  new pdo_mysql_helper($_SERVER['DOCUMENT_ROOT'] . "/inc/php/credentials.php");

//vars for all form fields
$name = $age = $email = $pass = $passConfirm = "";

//format data
if(isset($_POST["name"])){ $name = htmlspecialchars($_POST["name"]); }
if(isset($_POST["age"])){ $age = htmlspecialchars($_POST["age"]); }
if(isset($_POST["email"])){ $email = htmlspecialchars($_POST["email"]); }
if(isset($_POST["password"])){ $pass = htmlspecialchars($_POST["password"]);}
if(isset($_POST["passwordConfirm"])){ $passConfirm = htmlspecialchars($_POST["passwordConfirm"]); }

$ERROR_COUNT = 0;


/**
* Function that strips and validates an email address
* @author Eva Kuntz
* @param string $email email to be validated
* @return error if email is not validated, otherwise, returns empty string
**/
function validateEmail($email){
	if(check_if_user_exists($email) == 1){
		$GLOBALS['ERROR_COUNT']++;
		return "This email already exists.";
	}
	else if(!$email || strlen(trim($email)) < 1){
		$GLOBALS['ERROR_COUNT']++;
		return "Email is required";
	}
	else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		//check if valide email using filter_var function
		$GLOBALS['ERROR_COUNT']++;
		return "Email is not valid";
	}
	return "";
}

/**
* Function that validates user's proposed password.
* Password and Password Confirm must match.
* @author Eva Kuntz
* @param string $pass
* @param string $passConfirm
* @return error if not valid, otherwise returns empty string.
**/
function validatePassword($pass, $passConfirm){
	if(!$pass || !$passConfirm || strlen(trim($pass)) < 1 || strlen(trim($passConfirm)) < 1){
		$GLOBALS['ERROR_COUNT']++;
		return "Password is required";
	}
	else if($pass !== $passConfirm){
		$GLOBALS['ERROR_COUNT']++;
		return "Passwords must match";
	}
	return "";
}

/**
* Function that validates a user's entered name.
* Name must contain only alphabetic characters.
* @author Eva Kuntz
* @param string $name
* @return error if name is not valid, otherwise returns empty string.
**/
function validateName($name){
	$name = preg_replace('/\s+/', '', $name); //get rid of all whitespace
	if(!$name || strlen($name) < 1){
		$GLOBALS['ERROR_COUNT']++;
		return "Name is required";
	}
	else if(!ctype_alpha($name)){
		$GLOBALS['ERROR_COUNT']++;
		return "Name may only contain alphabetic characters";
	}

	return "";
}

/**
* Function that takes the user's email address
* and formats is correctly to be the user's username.
* @author Eva Kuntz
**/
function format_username($email){
	$result = "";
	$i = 0; //index
	
	while($email[$i] !== '@'){
	$result.=$email[$i];
	$i++;
	}

	return $result;
}


function check_if_user_exists($email){
	$email = format_username($email);

	$select = "SELECT * FROM user_info_no_fb u WHERE u.username=?";
	$GLOBALS['DBC']->query($select, array($email));

	if(count($GLOBALS['DBC']->fetch_all_assoc()) > 0){
		return 1;
	}
	return 0;
}

/*start of checking request type, validating account information,
* and inserting into database*/
if($_POST["request_type"] == 1){
	$nameError = validateName($name);
	$passError = validatePassword($pass, $passConfirm);
	$emailError = validateEmail($email);

	if($ERROR_COUNT != 0){
	//if there are errors, return the errors in json string
		echo json_encode( array("success"=>false,"nameError"=>$nameError, "emailError"=>$emailError, "passError"=>$passError));
	}else{
	//set session vars with user information
		$_SESSION['name'] = $name;
		$_SESSION['password'] = $pass;
		$_SESSION['email'] = $email;
		$_SESSION['age'] = $age;
	//echo back success json object
	echo json_encode( array("success"=>true));
	}
}
else if($_POST["request_type"] = 2){
	//echo json_encode( array("success"=>true));

	//iterate through food, music arrays
	$music = $food = "";
	for($i = 0; $i < sizeof($_POST["music"]); $i++){
		$music.=$_POST["music"][$i].",";
	}

	for($i = 0; $i < sizeof($_POST["food"]); $i++){
		$food.=$_POST["food"][$i].",";
	}

	$music = substr($music, 0, -1);
	$food = substr($food, 0, -1);

	//set cafe, arcade, and concerts variables
	if($_POST["cafe"] === "yes"){
		$cafe = 1;
	}else{
		$cafe = 2;
	}

	if($_POST["arcade"] === "yes"){
		$arcade = 1;
	}else{
		$arcade = 2;
	}

	if($_POST["concert"] === "yes"){
		$concerts = 1;
	}else{
		$concerts = 2;
	}

	//format the username
	$username = format_username($_SESSION["email"]);

	$insert = "INSERT into user_info_no_fb ( username, password,name, age_range,
	music, food, cafe, arcade, restaurant, concerts ) values (?, ?,?, ?, ?, ?, ?, ?, ?, ?)";
	
	//insert user info into db
	$DBC->query($insert, array($username, md5($_SESSION["password"]), $_SESSION["name"], $_SESSION["age"],
		$music, $food, $cafe, $arcade,$_POST["restaurant"], $concerts));

	$DBC->query("SELECT * FROM user_info_no_fb WHERE username ='$username'");
	if(count($DBC->fetch_all_assoc()) > 0){
		echo json_encode(array("success"=>true));
		return;
	}
	echo json_encode($DBC->fetch_all_assoc());
}

?>