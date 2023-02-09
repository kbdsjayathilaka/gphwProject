<?php

$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$rfid_uid = $_GET["rfid_uid"];

$sql = "SELECT account_balance FROM user WHERE rfid='$rfid_uid' LIMIT 1";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $account_balance = $row["account_balance"];


        







    }
} else {
    echo "No data found for the given RFID";
}

mysqli_close($conn);

?>
