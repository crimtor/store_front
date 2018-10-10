<?php
	// Note:
	// 1. symbol ".=" means concatenate
	function display_errors($errors) {
		$display = '<ul class="bg-danger">';
		foreach($errors as $error) {
			$display .= '<li class="text-danger">'.$error.'</li>';
		}
		$display .= '</ul>';
		return $display;
	}

	function page_redirect($location)
{
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$location.'">';
	exit;
}

	function sanitize($dirty) {
		return htmlentities($dirty, ENT_QUOTES, "UTF-8");
	}

	function money($number) {
		return '$'.number_format($number, 2);
	}
	function login($user_id){
		$_SESSION['SUser'] = $user_id;
		global $db;
		$date = date("Y-m-d H:i:s");
		$db->query("UPDATE admin SET last_login = '{$date}' WHERE id = '{$user_id}'");
		$_SESSION['success_flash'] = 'You are now logged in!';
		page_redirect("index.php");
	}

	function is_logged_in(){
		if(isset($_SESSION['SUser']) && $_SESSION['SUser'] > 0){
			return true;
		}else {
			return false;
		}
	}

	function loggin_error_redirect($url = 'login.php'){
		$_SESSION['error_flash'] = "You must be logged in to view that page";
		page_redirect($url);
	}
	function permission_error_redirect($url = 'index.php'){
		$_SESSION['error_flash'] = "You do not have permission to view that page";
		page_redirect($url);
	}

	function has_permission($permission = 'admin'){
		global $user_data;
		$permissions = explode(',', $user_data['permissions']);
		if(in_array($permission,$permissions, true)){
			return true;
		}else{
			return flase;
		}
	}

	function check_logged_in_status(){
		if(!is_logged_in()){
			loggin_error_redirect();
		}
	}
	function check_permission_status(){
		if(!has_permission()){
			permission_error_redirect();
		}
	}

	function format_date($date){
		return date("M d, Y h:i A", strtotime($date));
	}

	function get_catergories($childId){
		global $db;
		$id = sanitize($childId);
		$sql = "SELECT p.id AS 'pid', p.category AS 'parent', c.id
		AS 'cid', c.category AS 'child' FROM categories c
		INNER JOIN categories p
		ON c.parent = p.id
		WHERE c.id = '{$id}'";
		$query = $db->query($sql);
		$category = mysqli_fetch_assoc($query);
		return $category;
	}

	function sizesToArray($string){
		$trimmed = rtrim($string, ',');
		$size_array = explode(',', $trimmed);
		$array_to_return = array();
		foreach ($size_array as $size) {
			$inner_array = explode(':', $size);
			$array_to_return[] = array('size' => $inner_array[0], 'quantity' => $inner_array[1]);
			}
			return $array_to_return;
	}

	function sizesToString($size_array){
		$sizeString = null;
		foreach ($size_array as $size) {
			$sizeString .= $size['size'].':'.$size['quantity'].',';
			}
			$trimmed = rtrim($sizeString, ',');
			return $trimmed;
	}
