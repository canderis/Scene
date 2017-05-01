<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/php/pdo_mysql_helper.php");

$dbc = new pdo_mysql_helper($_SERVER['DOCUMENT_ROOT'] . "/inc/php/credentials.php");

$email_address = $_POST['email_address'];
$password = $_POST['password'];

if(!blank_fields($email_address, $password)){
   echo "You did not fill out the required fields.";
   //probably want to take them back to the page
}else{

  if(validate_status($dbc,$email_address,$password)){
    if(validate_email_address($email_address)){
      if(validate_user($email_address, $dbc)){
        if(validate_password(md5($password), $dbc)){
          header('Location: viewCompanyEvents.php');
          exit;
        }else{
          echo "Password was invalid";
          //probably want to take them back to the page
        }
      }else{
        echo "No account with that email address";
        //probably want to take them back to the page
      }
    }else{
      echo "Please use a valid email";
      //probably want to take them back to the page
      }
    }else{
      echo "Your request for an account has not been approved yet.";
    }
}

function blank_fields($email_address, $password){
  if(!isset($email_address) || trim($email_address) == ''
  || !isset($password) || trim($password) == ''){
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
  $validate = "SELECT * FROM company_info where email_address = ?";
  $dbc->query($validate, array($email_address));
  $result = $dbc->fetch_all_assoc();
  if(!empty($result)){
    return 1;
  }else{
    return 0;
  }
}

function validate_password($password, $dbc){
  $validate = "SELECT * FROM company_info where password = ? ";
  $dbc->query($validate, array($password));
  $result = $dbc->fetch_all_assoc();
  if(!empty($result)){
    return 1;
  }else{
    return 0;
  }
}
  
  function validate_status($dbc, $email, $pass){
    $validate = "SELECT * FROM company_info WHERE email_address=? AND password=? AND status=?";
    $dbc->query($validate, array($email,md5($pass),1));

  $result = $dbc->fetch_all_assoc();

     if(!empty($result)){
      return 1;
    }else{
      return 0;
    }

}

?>
