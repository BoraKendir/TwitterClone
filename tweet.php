<!-- tweet.php
    This php file is responsible for posting tweets
    It takes the tweet text from the text area in profile.php
    It inserts the tweet to the database
-->
<?php
    session_start();
    $servername = "localhost";
    $dbusername = "root";
    $password = "mysql";
    $dbname = "tw_clone";
    $conn = mysqli_connect($servername, $dbusername, $password, $dbname);
    $userId = $_SESSION["user_id"];
    //Making sure the connection is successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    $tweetText = htmlspecialchars($_POST['tweet']);
    //Query to insert the tweet to the database
    $tweetInsertquery = "INSERT INTO `tweets` (`tweet_text`, `tweet_user_id`, `tweet_date`) VALUES ('$tweetText','$userId',NOW());";
    
    if (mysqli_query($conn,$tweetInsertquery)) {
        echo "Tweet has been posted";
        mysqli_close($conn);
        //User is returned to profile.php
        header("Location: profile.php");
    } else {
        echo "Something went wrong!";
    }
    mysqli_close($conn);
?>