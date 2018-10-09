<?php
	$db = mysqli_connect('127.0.0.1', 'root', '', 'ecommerce_db');
	session_start();
	if(mysqli_connect_errno()) {
		echo 'Database connection failed with following errors: '.mysqli_connect_error();
		die();
	}

	require_once $_SERVER['DOCUMENT_ROOT'].'/my-php-shop/config.php';
	require_once BASEURL.'helpers/helpers.php';
	require BASEURL.'vendor/autoload.php';
	
	$cart_id = '';
	if(isset($_COOKIE[CART_COOKIE])){
		$cart_id = sanitize($_COOKIE[CART_COOKIE]);
	}

	if(isset($_SESSION['SUser'])){
		$user_id = $_SESSION['SUser'];
		$query = $db->query("SELECT * FROM admin WHERE id = '{$user_id}'");
		$user_data = mysqli_fetch_assoc($query);
		$fullName = explode(" ", $user_data['full_name']);
	}

if(isset($_SESSION['success_flash'])){
	echo '<div class="bg-success"><p class="text-success text-center">'.$_SESSION['success_flash'].'</p></div>';
	unset($_SESSION['success_flash']);
}
if(isset($_SESSION['error_flash'])){
	echo '<div class="bg-danger"><p class="text-danger text-center">'.$_SESSION['error_flash'].'</p></div>';
	unset($_SESSION['error_flash']);
}
