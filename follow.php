<!-- follow.php
    This php file is the functionality of the follow button
    It tries to follow the user that was the result of the search bar
    if the session user is already following the user , it gives feedback about it
	if not , session user follows the searched user
-->
<?php
session_start();

if (isset($_POST['follow'])) {
    $followerId = $_SESSION["user_id"];
    $followingId = $_POST['user_id'];
    $followDate = date("Y-m-d");

    $servername = "localhost";
    $dbusername = "root";
    $password = "mysql";
    $dbname = "tw_clone";
    $conn = mysqli_connect($servername, $dbusername, $password, $dbname);
	//Making sure the connection is successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if the user is already following the target user
    $query = "SELECT * FROM follows WHERE follower_id = '$followerId' AND following_id = '$followingId'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<h1 >You are already following this user.</h1>";
        header("homepage.php");
    } else {
        // Insert the follow relationship
        $insertQuery = "INSERT INTO follows (follower_id, following_id, follow_date) VALUES ('$followerId', '$followingId', '$followDate')";

        if (mysqli_query($conn, $insertQuery)) {
            echo "Followed successfully!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }

    mysqli_close($conn);
}
?>
