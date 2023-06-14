<!--- register.php
    This file registers a user to the database
    It checks if the username is already in the database
    If it is not, it registers the user
--->
<!DOCTYPE html>
<html>
<body>
    <h1>Registration Result</h1>
<?php
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "mysql";
    $dbname = "tw_clone";

    $conn = mysqli_connect($servername, $dbusername, $dbpassword, $dbname);
    
    //Making sure the connection is successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    //User inputs as forums
    $name = $_POST["name"]; 
    $username = $_POST["username"];
    $password = $_POST["password"];

    //Query to check if the username is already in the database
    $checkQuery = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn,$checkQuery) or die("11");
    
    if (mysqli_num_rows($result) > 0) {
        echo "Username already exists";
        mysqli_close($conn);
        exit();
    }
    //Query to insert the user to the database, their id is auto incremented
    $insertQuery = "INSERT INTO `users` (`name`, `username`, `password`, `register_date`) VALUES
    ('$name','$username','$password',CURDATE())";

    if (mysqli_query($conn,$insertQuery)) {
        echo "Registration has been successful";
        mysqli_close($conn);
        exit();
    } else {
        echo "Something went wrong!";
    }
    mysqli_close($conn);
?>
</body>
</html>