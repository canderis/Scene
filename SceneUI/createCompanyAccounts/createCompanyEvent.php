<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/php/pdo_mysql_helper.php");

$dbc = new pdo_mysql_helper($_SERVER['DOCUMENT_ROOT'] . "/inc/php/credentials.php");

$title = $_POST['title'];
$description = $_POST['description'];
$address = $_POST['address'];
$type = $_POST['type'];

if(!blank_fields($title, $description, $address, $type)){
   echo "You did not fill out the required fields.";
   //probably want to take them back to the page
}else{
  insert_into_company_events($dbc, $title, $description, $address, $type);
  header('Location: viewCompanyEvents.php');
  exit;
}

function blank_fields($title, $description, $address, $type){
  if(!isset($title) || trim($title) == ''
  || !isset($description) || trim($description) == ''
  || !isset($address) || trim($address) == ''
  || !isset($type) || trim($type) == ''){
    return 0;
  }else{
    return 1;
  }
}

function insert_into_company_events($dbc, $title, $description, $address, $type){
  $insert = "INSERT INTO company_events (title, description, address, type)
  VALUES ('$title', '$description', '$address', '$type')";

  $dbc->query($insert, array('title', 'description', 'address', 'type'));
}


?>
