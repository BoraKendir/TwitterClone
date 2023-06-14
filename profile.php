<!-- profile.php 
    This file displays the user's profile page.
    
    The user can see their tweets, followers, and followings,
    also their own tweets.
    
    User can tweet things, go to their homepage, or logout.
-->
<!DOCTYPE html>
<html>
<head>
    <title>Your Profile</title>
    <link rel="stylesheet" href="css/profile.css" />
    <link href="https://fonts.cdnfonts.com/css/chirp-2" rel="stylesheet">

</head>
<?php
    session_start();
    //Getting and setting $_SESSION variables
    if (isset($_SESSION["username"])) {
        $SessionUsername = $_SESSION["username"];
        $SessionUserID = $_SESSION["user_id"];
        $SessionRealName = $_SESSION["name"];
    } else {
        echo "User not logged in.";
    }
?>
<body>
    <h1>Welcome, <?php echo $_SESSION["name"]?>!</h1>
<?php
    $servername = "localhost";
    $dbusername = "root";
    $password = "mysql";
    $dbname = "tw_clone";
    $conn = mysqli_connect($servername, $dbusername, $password, $dbname);
    $userId = $_SESSION["user_id"];
    //Making sure connection is successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    //In my DB design, numbers of objects(followers,tweets,followings etc) are not stored
    //to get the number of things, I defined functions
    //They are very similiar since they just count the number of rows in a query result
    //Their names are self-explanatory
    function getTweetNum($userId, $conn) {
        $query = "SELECT COUNT(*) as tweet_count FROM tweets WHERE tweet_user_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row['tweet_count'];
        }
        return 0;
    }

    function getFollowerNum($userId, $conn) {
        $query = "SELECT COUNT(*) as follower_count FROM follows WHERE following_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row['follower_count'];
        }
        return 0;
    }

    function getFollowingNum($userId, $conn) {
        $query = "SELECT COUNT(*) as following_count FROM follows WHERE follower_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row['following_count'];
        }
        return 0;
    }

    function getAccountCreationDate($userId, $connection) {
        $query = "SELECT register_date FROM users WHERE user_id = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row['register_date'];
        }
        return null;
    }

    //Using the functions above to get the numbers
    $tweetCount = getTweetNum($SessionUserID, $conn);

    $followerCount = getFollowerNum($SessionUserID, $conn);

    $followingCount = getFollowingNum($SessionUserID, $conn);

    $accountCreationDate = getAccountCreationDate($SessionUserID, $conn);

    //Displaying the information
    echo '<div class="information-box">';
    echo "Number of tweets: " . $tweetCount . "<br>";
    echo "<br>";
    echo "Number of followers: " . $followerCount . "<br>";
    echo "<br>";
    echo "Number of followings: " . $followingCount . "<br>";
    echo "<br>";
    echo "Account creation date: " . $accountCreationDate . "<br>";
    echo '</div>';

    mysqli_close($conn);
?>
    <div class="tweet-box">
        <form method="post" action="tweet.php">
            <textarea name="tweet" rows="5" cols="50" maxlength="140" placeholder="What's on your mind?"></textarea>
            <input type="submit" value="Tweet" />
        </form>
    </div>
    <h1>Your Tweets:</h1>
<?php

    $servername = "localhost";
    $dbusername = "root";
    $password = "mysql";
    $dbname = "tw_clone";
    $conn = mysqli_connect($servername, $dbusername, $password, $dbname);
    $userId = $_SESSION["user_id"];
    // Making sure connection is successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // This is where I call my procedure to get the tweets of the user
    $stmt = mysqli_prepare($conn, "CALL GetUserTweets(?)");
    mysqli_stmt_bind_param($stmt, "i", $userId);

    // Execute the stored procedure
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $tweetText = $row["tweet_text"];
        $tweetDate = $row["tweet_date"];
    
        //I like to get the tweets in a div so I can style them if I have time :/
        echo '<div class="individual-tweet">';
        echo '<p>Tweet: ' . $tweetText . '</p>';
        echo '<p>Date: ' . $tweetDate . '</p>';
        echo '</div>';
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
?>
    <footer>
        <div class="profile-btn">
            <form method="get" action="homepage.php">
                <input type="submit" value="Homepage" />
            </form>
        </div>
        <div class="logout-btn">
            <form method="get" action="logout.php">
                <input type="submit" value="Logout" />
            </form>
        </div>
    </footer>
</body>
</html>
