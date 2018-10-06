<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/my-php-shop/core/init.php';
	include 'includes/head.php';
  include 'includes/navigation.php';
	check_logged_in_status();
  check_permission_status();

	if(isset($_GET['delete'])){
		$delete_id = sanitize($_GET['delete']);
		$db->query("DELETE FROM admin WHERE id = '{$delete_id}'");
		$_SESSION['success_flash'] = "User has been successfully deleted";
		page_redirect("users.php");
	}
	if(isset($_GET['add'])){
		$name = ((isset($_POST['name']))?sanitize($_POST['name']): '');
		$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
	  $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
	  $password = trim($password);
		$confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
	  $confirm = trim($confirm);
		$permissions = ((isset($_POST['permissions']))?sanitize($_POST['permissions']):'');
	  $errors = array();

		if($_POST){
			$required = array('name', 'email', 'password', 'confirm', 'permissions');
			$emailQuery = $db->query("SELECT * FROM admin WHERE email = '{$email}'");
			$emailCount = mysqli_num_rows($emailQuery);

			//form validation
			foreach ($required as $req) {
	    	if(empty($_POST[$req])){
	      $errors[] = 'You must fill out every field.';
				break;
			}
		}

		//check to make sure email is unique
		if($emailCount !=0){
			$errors[] = "That email already is registered in database.";
		}

		//validate Email
		if(!filter_var($email.FILTER_VALIDATE_EMAIL)){
			$errors[] = "Please enter a valid email address";
		}

	    //validate password length
	    if(strlen($password) < 6){
	      $errors[] = "Password must be at least 6 characters long.";
	    }

	    // Check to make sure new pass equals Confirm
	    if($password != $confirm){
	      $error[] = "The new password and confirm password do not match";
	    }

	    //check for $errors
	    if(!empty($errors)){
	      echo display_errors($errors);
	    }else{
				$new_hashed = password_hash($password, PASSWORD_DEFAULT);
	      //Add user to DB
	      $db->query("INSERT INTO admin (full_name, email, password, permissions) VALUES ('{$name}', '{$email}', '{$new_hashed}', '{$permissions}')");
	      $_SESSION['success_flash'] = "User has been added to Database.";
	      page_redirect("users.php");
	    }
	  }

		?>
			<h2 class="text-center"> Add A New User</h2><hr>
			<form action="users.php?add=1" method="post">
				<div class="form-group">
					<label for="name"> Full Name : </label>
					<input type="text" name="name" id="name" class="form-control" value="<?=$name;?>">
				</div>
				<div class="form-group">
					<label for="email"> Email : </label>
					<input type="email" name="email" id="email" class="form-control" value="<?=$email;?>">
				</div>
				<div class="form-group">
					<label for="password"> Password : </label>
					<input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
				</div>
				<div class="form-group">
					<label for="confirm"> Confirm Password : </label>
					<input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm;?>">
				</div>
				<div class="form-group">
					<label for="permissions"> Permissions : </label>
					<select class="form-control" name="permissions">
						<option value=<?=(($permissions == '')?' selected': '');?>></option>
						<option value="editor"><?=(($permissions == 'editor')?' selected': '');?>Editor</option>
						<option value="admin,editor"><?=(($permissions == 'admin.editor')?' selected': '');?>Admin</option>
					</select>
				</div>
				<div class="form-group">
					<a href="users.php" class="btn btn-default">Cancel</a>
					<input type="submit" value="Add User" class="btn btn-primary">
				</div>
			</form>
		<?php
	}else{
	$userQuery = $db->query("SELECT * FROM admin ORDER BY full_name");

?>
<h2>Users</h2>
<a href="users.php?add=1" class="btn btn-sucess" id="add-user-button">Add New User</a>
<hr>
<table class="table table-bordered table-striped table-condensed">
	<thead>
		<th></th>
		<th>Name</th>
		<th>Email</th>
		<th>Join Date</th>
		<th>Last Login</th>
		<th>Permissions</th>
	</thead>
	<tbody>
		<?php while($user = mysqli_fetch_assoc($userQuery)): ?>
		<tr>
			<td>
				<?php if($user['id'] != $user_data['id']): ?>
					<a href="users.php?delete=<?=$user['id'];?>" class="btn btn-default btn-xs">
						<span class="glyphicon glyphicon-remove-sign"></span></a>
				<?php endif; ?>
			</td>
			<td><?=$user['full_name'];?></td>
			<td><?=$user['email'];?></td>
			<td><?=format_date($user['join_date']);?></td>
			<td><?=(($user['last_login'] == $user['join_date'])?'Never':format_date($user['last_login']));?></td>
			<td><?=$user['permissions'];?></td>
		</tr>
	<?php endwhile; ?>
	</tbody>

<?php } include 'includes/footer.php'; ?>
