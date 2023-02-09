<?php 
	session_start();
	$_SESSION = array();
	if (isset($_COOKIE[session_name()])) {
		setcookie(session_name(), '', time()-86400, '/');
	}
	session_destroy();	
 ?>
<?php session_start(); ?>
<?php require_once('connection.php'); ?>
<?php require_once('functions.php'); ?>
<?php 
  if (!isset($_GET['user_id'])) {
    header('Location: index.php?access_blocked');
  }
  // check for form submission
  if (isset($_POST['submit'])) {

    $errors = array();

    // check if the username and password has been entered
    if (!isset($_POST['email']) || strlen(trim($_POST['email'])) < 1 ) {
      $errors[] = 'Username is Missing / Invalid';
    }

    if (!isset($_POST['password']) || strlen(trim($_POST['password'])) < 1 ) {
      $errors[] = 'Password is Missing / Invalid';
    }

    // check if there are any errors in the form
    if (empty($errors)) {
      // save username and password into variables
      $email    = mysqli_real_escape_string($connection, $_POST['email']);
      $password   = mysqli_real_escape_string($connection, $_POST['password']);
      $hashed_password = sha1($password);
      

      // prepare database query
      $query = "SELECT * FROM user 
            WHERE email = '{$email}' 
            AND password = '{$hashed_password}' 
            LIMIT 1";

      $result_set = mysqli_query($connection, $query);

      verify_query($result_set);

        if (mysqli_num_rows($result_set) == 1) {
          // valid user found
          $user = mysqli_fetch_assoc($result_set);
          $_SESSION['user_id'] = $user['id'];
          $_SESSION['first_name'] = $user['first_name'];
           $_SESSION['is_deleted'] = $user['is_deleted'];
          // updating last login
        $query = "UPDATE user SET last_login = NOW() ";
        $query .= "WHERE id = {$_SESSION['user_id']} LIMIT 1";

        $result_set = mysqli_query($connection, $query);

        verify_query($result_set);
          // redirect to delete-user.php
          $url="delete-user.php?user_id=".$user['id'];
          header('Location: '.$url);
          // user name and password invalid
          $errors[] = 'Invalid Username / Password';
        }
      } 
    }
  
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Delete User-Login</title>
  <link rel="stylesheet" href="style.css">
  
  
</head>
<body>

  <div class="container">

    <form action="index-delete.php" method="post">
      
      <fieldset>
        <h1>Log in again to delete the user</h1>

        <?php 
          if (isset($errors) && !empty($errors)) {
            echo '<p style="color: red; font-size: 26px;">Invalid Username / Password</p>';
          }
        ?>
        

        <p>
          <label for="">Username:</label>
          <input type="text" name="email" id="" placeholder="Email Address" required>
        </p>

        <p>
          <label for="">Password:</label>
          <input type="password" name="password" id="" placeholder="Password" required>
        </p>

        <p>
          <button type="submit" name="submit" id="submitBtn" >Log In</button>
        </p>
         <p>
           <button  onclick = "window.location.href='index.php' ;"   id="cancelBtn">Cancel</button>
         </p> 
      </fieldset>

    </form>   

  </div> <!-- .login -->
</body>
</html>
<?php mysqli_close($connection); ?>


