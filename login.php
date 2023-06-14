<!-- 
    login.php
    This php file authenticates the user and redirects to the homepage if the user is authenticated.
    to do this, it checks the if the users credentials are in the database.
    if it is , it logs them in and saves some information on $_SESSION global variable.
    if it is not, it displays an error message.
-->
<!DOCTYPE html>
<html>
<body>
    <h1>Login Result</h1>
<?php
$servername = "localhost";
$username = "root";
$password = "mysql";
$dbname = "tw_clone";

$conn = mysqli_connect($servername, $username, $password, $dbname);
//Making sure the connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 
//These are imputs from the user
$username = $_POST["username"];
$password = $_POST["password"];

//Query to check if the user is in the database
$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$result = mysqli_query($conn,$sql) or die("11");

//Query to get the user_id of the user, it has some uses in procedures
$sql2 = "SELECT user_id FROM users WHERE username = '$username' AND password = '$password'";
$result2 = mysqli_query($conn,$sql2) or die("11");

//Query to get the name of the user to show it on profile.php
$sql3 = "SELECT name FROM users WHERE username = '$username' AND password = '$password'";
$result3 = mysqli_query($conn,$sql3) or die("11");
//Fetching the query results
$row = mysqli_fetch_assoc($result2);
$userId = $row["user_id"];

$row2 = mysqli_fetch_assoc($result3);
$userRealName = $row2["name"];

//if the user is in database
if (mysqli_num_rows($result) > 0) {
    echo "Login Success";
    session_start();
    header("Location: homepage.php");
    $_SESSION["username"] = $username;
    $_SESSION["user_id"] = $userId;
    $_SESSION["name"] = $userRealName;
} else {
    echo "Invalid username or password";
}
mysqli_close($conn);
?>
</body>
</html>
