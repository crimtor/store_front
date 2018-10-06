<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/my-php-shop/core/init.php';
	include 'includes/head.php';
  check_logged_in_status();
  $hashed = $user_data['password'];
  $old_password = ((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
  $old_password = trim($old_password);
  $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
  $password = trim($password);
  $confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
  $confirm = trim($confirm);
  $new_hashed = password_hash($password, PASSWORD_DEFAULT);
  $errors = array();
?>


<?php
  if($_POST){
    //form validation
    if(empty($_POST['old_password']) || empty($_POST['password']) ||  empty($_POST['confirm'])){
      $errors[] = 'You must provide an old password, new password and confirm password.';
    }

    //validate password length
    if(strlen($password) < 6){
      $errors[] = "Password must be at least 6 characters long.";
    }

    // Check to make sure new pass equals Confirm
    if($password != $confirm){
      $error[] = "The new password and confirm password do not match";
    }

    //check PASSWORD
    if(!password_verify($old_password, $hashed)){
      $errors[] = 'Your old password you entered does not match our records';
    }

    //check for $errors
    if(!empty($errors)){
      echo display_errors($errors);
    }else{
      //change password
      $user_id = $user_data['id'];
      $db->query("UPDATE admin SET password = '{$new_hashed}' WHERE id = '{$user_id}'");
      $_SESSION['success_flash'] = "Your Password was updated successfully.";
      page_redirect("index.php");
    }
  }
?>
  <div id="login-form">
    <h2 class="text-center">Change Password</h2><hr>
    <form action="change_password.php" method="post">
      <div class="form-group">
        <label for="old_password">Old Password:</label>
        <input type="password" name="old_password" class="form-control" value="<?=$old_password;?>">
      </div>
      <div class="form-group">
        <label for="password">New Password:</label>
        <input type="password" name="password" class="form-control" value="<?=$password;?>">
      </div>
      <div class="form-group">
        <label for="confirm">Confirm New Password:</label>
        <input type="password" name="confirm" class="form-control" value="<?=$confirm;?>">
      </div>
      <div class="form-group">
        <a href="index.php" class="btn btn-default">Cancel</a>
        <input type="submit" value="Login" class="btn btn-primary">
      </div>
    </form>
    <p class="text-right"><a href="/my-php-shop/index.php" alt="home">Visit Website</a></p>
    </div>

  <?php  include 'includes/footer.php'; ?>
