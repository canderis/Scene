<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/php/pdo_mysql_helper.php");

$dbc = new pdo_mysql_helper($_SERVER['DOCUMENT_ROOT'] . "/inc/php/credentials.php");

$company_name = $_POST['company_name'];
$email_address = $_POST['email_address'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

if(!blank_fields($company_name, $email_address, $password, $confirm_password)){
   echo "You did not fill out the required fields.";
   //probably want to take them back to the page
}else{
  if(validate_email_address($email_address)){
    if(validate_user($email_address, $dbc)){
      if(validate_password($password, $confirm_password)){
        insert_into_company_info($dbc, $company_name, $email_address, md5($password));
       // header('Location: viewCompanyEvents.php');
        //echo 'alert("Your request for a company account has been submitted!");';
        header('Location: index.html');
        exit;
      }else{
        echo "Passwords did not match";
        //probably want to take them back to the page
      }
    }else{
      echo "Already account using that email address";
      //probably want to take them back to the page
    }
  }else{
    echo "Please use a valid email";
    //probably want to take them back to the page
  }
}

function blank_fields($company_name, $email_address, $password, $confirm_password){
  if(!isset($company_name) || trim($company_name) == ''
  || !isset($email_address) || trim($email_address) == ''
  || !isset($password) || trim($password) == ''
  || !isset($confirm_password) || trim($confirm_password) == ''){
    return 0;

  }else{
    return 1;
  }
}

function validate_email_address($email_address){
  if (filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
    return 1;
  } else {
    return 0;
  }
}

function validate_user($email_address, $dbc){
  $validate = "SELECT * FROM company_info where email_address = ? ";
  $dbc->query($validate, array($email_address));
  $result = $dbc->fetch_all_assoc();
  if(empty($result)){
    return 1;
  }else{
    return 0;
  }
}

function validate_password($password, $confirm_password) {
  if($password != $confirm_password){
    return 0;
  }else{
    return 1;
  }
}

function insert_into_company_info($dbc,$company_name, $email_address, $password, $account_type = 2){
  $insert = "INSERT INTO company_info (company_name, email_address, password, account_type)
  VALUES (?,?,?,?)";

  $dbc->query($insert, array($company_name,$email_address,$password,$account_type));
}
?>
