<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/my-php-shop/core/init.php';

  unset($_SESSION['SUser']);
  page_redirect("login.php");
  ?>
