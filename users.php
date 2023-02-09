<?php session_start(); ?>
<?php require_once('connection.php'); ?>
<?php require_once('functions.php'); ?>
<?php 
	// checking if a user is logged in
	if (!isset($_SESSION['user_id'])) {
		header('Location: index.php?access_blocked');
	}
	
	if ($_SESSION['is_deleted']==1) {
		header('Location: index.php?is_deleted=1');
	}

	$user_list = '';
	$search = '';
	// getting the list of users
	if ( isset($_GET['search']) ) {
		$search = mysqli_real_escape_string($connection, $_GET['search']);
		$query = "SELECT * FROM user WHERE (first_name LIKE '%{$search}%' OR last_name LIKE '%{$search}%' OR email LIKE '%{$search}%') AND is_deleted=0 ORDER BY first_name";					
	} else {
		$query = "SELECT * FROM user WHERE is_deleted=0 ORDER BY first_name";
	}
	$users = mysqli_query($connection, $query);

	verify_query($users);
		while ($user = mysqli_fetch_assoc($users)) {
			$user_list .= "<tr>";
			$user_list .= "<td>{$user['first_name']}</td>";
			$user_list .= "<td>{$user['last_name']}</td>";
			$user_list .= "<td>{$user['account_balance']}</td>";
			$user_list .= "<td>{$user['last_login']}</td>";
			$user_list .= "<td><a href=\"index-edit.php?user_id={$user['id']}\" class=\"edit-link\">Edit</a></td>";
			$user_list .= "<td><a href=\"index-delete.php?user_id={$user['id']}\"class=\"delete-link\">Delete</a></td>";
			$user_list .= "</tr>";
		}
	
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Users</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>


	<header>
		<div class="appname">Bus Card Instad of Money: User Management System</div>
		<div class="loggedin">User ID: <b><?php echo $_SESSION['user_id']; ?>&nbsp; <a href="logout.php" id="myLink">Log Out</a>
			<a href="add-user.php"  id="myLink" >+ Add New User </a></b>
		</div>
	</header>
	<main>
		<h1 >Users </h1>
		<div class="search"><p>
			<form action="users.php" method="get">
				<input type="text" name="search" value="<?php echo $search; ?>" id="" placeholder="Type First Name, Last Name or Email Address and Press Enter" autofocus>
			</p>
			</form>
	</div>
		<table class="masterlist">
			<tr>
				<th>First Name</th>
				<th>Last Name</th>
				<th>Account Balance</th>
				<th>Last Login</th>
				<th>Edit</th>
				<th>Delete</th>
			</tr>

			<?php echo $user_list; ?>

		</table>
		
		
	</main>
</body>
</html>