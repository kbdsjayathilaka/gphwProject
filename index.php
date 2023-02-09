<?php session_start(); ?>
<?php require_once('connection.php'); ?>
<?php require_once('functions.php'); ?>
<?php 

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

          // redirect to users.php
          header('Location: users.php');
        } else {
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
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
  
  
</head>
<body>

  <div class="container">

    <form action="index.php" method="post">
      
      <fieldset>
        <h1>LOGIN</h1><h1><a href="add-user.php" id="myLink"   >+ Add New User</a></h1><br><br>

        <?php 
          if (isset($errors) && !empty($errors)) {
            echo '<p style="padding: 10px;
                     background-color: #ff6666;
                      color: #ffffff;
                        text-align: center;
                          border-radius: 15px;
                             font-size: 16px;">Invalid Username or Password</p>';
          }
        ?>
        <?php 
          if (isset($_GET['logout'])) {
            echo '<p class="info">You have successfully logged out from the system</p>';
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

      </fieldset>

    </form>   

  </div> <!-- .login -->
</body>
</html>
<?php mysqli_close($connection); ?>

