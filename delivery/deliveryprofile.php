<?php
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: deliverylogin.php");
    exit;
}

// Include config file
require_once "connect.php";

// Define variables and initialize with empty values
$username = $name = $email = $gender = "";
$username = $_SESSION["username"];

// Prepare a select statement
$sql = "SELECT name, email, gender FROM delivery_users WHERE username = ?";

if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("s", $param_username);

    // Set parameters
    $param_username = $username;

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        $stmt->store_result();
        
        // Check if username exists, if yes then fetch the data
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($name, $email, $gender);
            if ($stmt->fetch()) {
                // Display account info below
            }
        } else {
            // Username doesn't exist, display an error message
            echo "There was an error retrieving your account details.";
        }
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; }
        .container { width: 350px; padding: 20px; background-color: white; margin: 50px auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { color: #4CAF50; }
        p { margin: 10px 0; }
        .logout { background-color: #4CAF50; color: white; padding: 10px 20px; text-align: center; display: block; margin-top: 20px; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Profile</h1>
        <p><b>Name:</b> <?php echo htmlspecialchars($name); ?></p>
        <p><b>Email:</b> <?php echo htmlspecialchars($email); ?></p>
        <p><b>Gender:</b> <?php echo htmlspecialchars($gender); ?></p>
        <a href="deliverylogout.php" class="logout">Logout</a>
    </div>
</body>
</html>