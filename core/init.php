<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/my-php-shop/config.php';

	$db = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	session_start();
	if(mysqli_connect_errno()) {
		echo 'Database connection failed with following errors: '.mysqli_connect_error();
		die();
	}

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
	echo '<div class="bg-success"><p class="text-light text-center">'.$_SESSION['success_flash'].'</p></div>';
	unset($_SESSION['success_flash']);
}
if(isset($_SESSION['error_flash'])){
	echo '<div class="bg-warning"><p class="text-danger text-center">'.$_SESSION['error_flash'].'</p></div>';
	unset($_SESSION['error_flash']);
}
