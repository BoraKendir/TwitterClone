<!-- search.php
    This php file is the functionality of the search bar
    It searches the username in the database and displays the results
    if the user exists, it creates a button for the session user to follow the searched user
-->
<!DOCTYPE html>
<html>
<head>
    <title>Search</title>
    <link rel="stylesheet" href="css/seach.css" />
    <link href="https://fonts.cdnfonts.com/css/chirp-2" rel="stylesheet">
</head>
<body>
    <div class="search_container">
        <h3>Search</h3>
        <form method="get" action="search.php">
            <div class="search-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required />
                <input type="submit" value="Search" />
            </div>
        </form>
    </div>
<?php
    session_start();
    $servername = "localhost";
    $dbusername = "root";
    $password = "mysql";
    $dbname = "tw_clone";
    $conn = mysqli_connect($servername, $dbusername, $password, $dbname);
    $userId = $_SESSION["user_id"];

    //Make sure the connection is successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if (isset($_GET['username'])) {
        $username = $_GET['username'];

        // Query to search the user in the database
        $query = "SELECT * FROM users WHERE username LIKE '%$username%'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            
            // Display search results
            echo "<h2>Search Results</h2>";
            while ($row = mysqli_fetch_assoc($result)) {
                $SearchedUserId = $row['user_id'];
                $SearchedName = $row['name'];
                $SearchedUsername = $row['username'];
        
                // Display user information
                echo "<p>Username: $SearchedUsername</p>";
                echo "<p>Full Name: $SearchedName</p>";
        
                echo "<div class='follow-btn'>";
                echo "<form method='post' action='follow.php'>";
                echo "<input type='hidden' name='user_id' value='$SearchedUserId' />";
                echo "<input type='submit' name='follow' value='Follow' />";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "No results found.";
        }
        mysqli_free_result($result);
    }
    mysqli_close($conn);
?>
    <hr>
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