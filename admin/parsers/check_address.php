<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/my-php-shop/core/init.php';
  $name = sanitize($_POST['full_name']);
  $email = sanitize($_POST['email']);
  $street = sanitize($_POST['street']);
  $street2 = sanitize($_POST['street2']);
  $city = sanitize($_POST['city']);
  $state = sanitize($_POST['state']);
  $country = sanitize($_POST['country']);
  $zip = sanitize($_POST['zip']);
  $errors = array();
  $required = array(
    'full_name' => 'Full Name',
    'email' => 'Email',
    'street' => 'Street Address',
    'city' => 'City',
    'state' => 'State',
    'zip' => 'Zip Code'
  );

  //Check required fields
  foreach ($required as $field => $display_name) {
    if(empty($_POST[$field]) || $_POST[$field] == ''){
      $errors[] = $display_name. ' is a required field';
    }
  }

  //validate Email
  if(!filter_var($email.FILTER_VALIDATE_EMAIL)){
    $errors[] = "Please enter a valid email address";
  }

  if(!empty($errors)){
    echo display_errors($errors);
  }else{
		echo "passed";
  }
?>
