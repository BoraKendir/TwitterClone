<!-- homepage.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Homepage</title>
    <link rel="stylesheet" href="css/homepage.css" />
    <link href="https://fonts.cdnfonts.com/css/chirp-2" rel="stylesheet">
</head>
<?php
    session_start();
    // Access the username from the session
    if (isset($_SESSION["username"])) {
        $username = $_SESSION["username"];
    } else {
        echo "User not logged in.";
    }
?>
<body>
    <h1>Welcome @<?php echo $_SESSION["username"]?></h1>
    <div class="search-container">
        <form method="get" action="search.php">
            <div class="search-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required />
                <input type="submit" value="Search" />
            </div>
        </form>
    </div>
<?php
    // Create connection
    $servername = "localhost";
    $dbusername = "root";
    $password = "mysql";
    $dbname = "tw_clone";
    $conn = mysqli_connect($servername, $dbusername, $password, $dbname);
    $userId = $_SESSION["user_id"];
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare the statement
    $stmt = mysqli_prepare($conn, "CALL GetFollowerTweets(?)");
    mysqli_stmt_bind_param($stmt, "i", $userId);

    // Execute the stored procedure
    mysqli_stmt_execute($stmt);

    // Get the result set
    $result = mysqli_stmt_get_result($stmt);

    // Process the result set
    while ($row = mysqli_fetch_assoc($result)) {
        // Access the tweet_text and username columns
        $tweetText = $row["tweet_text"];
        $tweetUsername = $row["tweet_user"];
        $tweetDate = $row["tweet_date"];
    
        // Generate HTML code for the tweet box
        echo '<div class="tweet-box">';
        echo '<p>Username: ' . $tweetUsername . '</p>';
        echo '<p>Tweet: ' . $tweetText . '</p>';
        echo '<p>Date: ' . $tweetDate . '</p>';
        echo '</div>';
    }
    

    // Close the statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
?>
    <footer>
        <div class="profile-btn">
            <form method="get" action="profile.php">
                <input type="submit" value="Profile" />
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
