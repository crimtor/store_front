<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/my-php-shop/core/init.php';
	include 'includes/head.php';

  $email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
  $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
  $password = trim($password);
  $errors = array();
?>


<?php
  if($_POST){
    //form validation
    if(empty($_POST['email']) || empty($_POST['password'])){
      $errors[] = 'You must provide an email and password.';
    }
    //validate Email
    if(!filter_var($email.FILTER_VALIDATE_EMAIL)){
      $errors[] = "Please enter a valid email address";
    }

    //validate password length
    if(strlen($password) < 6){
      $errors[] = "Password must be at least 6 characters long.";
    }
     //check if email exits
    $query = $db->query("SELECT * FROM admin WHERE email = '{$email}'");
    $user = mysqli_fetch_assoc($query);
    $userCount = mysqli_num_rows($query);
    if($userCount < 1){
      $errors[] = 'What you entered does not match our records';
    }
    //check PASSWORD
    if(!password_verify($password, $user['password'])){
      $errors[] = 'What you entered does not match our records';
    }

    //check for $errors
    if(!empty($errors)){
      echo display_errors($errors);
    }else{
      //log user in
      $user_id = $user['id'];
      login($user_id);
    }
  }
?>
  <div id="login-form">
    <h2 class="text-center">Login</h2><hr>
    <form action="login.php" method="post">
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" name="email" class="form-control" value="<?=$email;?>">
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" name="password" class="form-control" value="<?=$password;?>">
      </div>
      <div class="form-group">
        <input type="submit" value="Login" class="btn btn-primary">
      </div>
    </form>
    <p class="text-right"><a href="/my-php-shop/index.php" alt="home">Visit Website</a></p>
    </div>

  <?php  include 'includes/footer.php'; ?>
