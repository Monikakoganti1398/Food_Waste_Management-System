<?php
ob_start();
include '../connection.php';  // Make sure this file calls session_start()
include("connect.php");       // Include your additional connection setup if needed

// Check if the session variable 'Did' is set
if (!isset($_SESSION['Did'])) {
    // Redirect to the login page if 'Did' is not set
    header("location:deliverylogin.php");
    exit;
} else {
    $id = $_SESSION['Did']; // Now you can safely use this variable
}

// Check if the session name is set or not empty
if (empty($_SESSION['name'])) {
    header("location:deliverylogin.php");
    exit;
}
$name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Delivery</title>
    <link rel="stylesheet" href="delivery.css">
    <link rel="stylesheet" href="../home.css">
</head>
<body>
<header>
    <div class="logo">Food <b style="color: #06C167;">Donate</b></div>
    <div class="hamburger">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
    </div>
    <nav class="nav-bar">
        <ul>
            <li><a href="delivery.php">Home</a></li>
            <li><a href="openmap.php">Map</a></li>
            <li><a href="deliverymyord.php" class="active">My Orders</a></li>
        </ul>
    </nav>
</header>

<script>
    hamburger=document.querySelector(".hamburger");
    hamburger.onclick = function() {
        navBar=document.querySelector(".nav-bar");
        navBar.classList.toggle("active");
    }
</script>

<style>
    .itm {
        background-color: white;
        display: grid;
    }
    .itm img {
        width: 400px;
        height: 400px;
        margin-left: auto;
        margin-right: auto;
    }
    p {
        text-align: center; font-size: 28px; color: black; 
    }
    @media (max-width: 767px) {
        .itm img {
            width: 350px;
            height: 350px;
        }
    }
</style>

<div class="itm">
    <img src="../img/deliveryservice.png" alt="" width="400" height="400"> 
</div>

<div class="get">
    <?php
    $sql = "SELECT fd.Fid AS Fid, fd.name, fd.phoneno, fd.date, fd.delivery_by, fd.address as From_address, 
    ad.name AS delivery_person_name, ad.address AS To_address
    FROM food_donations fd
    LEFT JOIN admin ad ON fd.assigned_to = ad.Aid where delivery_by='$id';";

    $result = mysqli_query($connection, $sql);

    if (!$result) {
        die("Error executing query: " . mysqli_error($connection));
    }

    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    if (isset($_POST['food']) && isset($_POST['delivery_person_id'])) {
        $order_id = $_POST['order_id'];
        $delivery_person_id = $_POST['delivery_person_id'];
        $sql = "UPDATE food_donations SET delivery_by = $delivery_person_id WHERE Fid = $order_id";
        $result = mysqli_query($connection, $sql);

        if (!$result) {
            die("Error assigning order: " . mysqli_error($connection));
        }
        header('Location: ' . $_SERVER['REQUEST_URI']);
        ob_end_flush();
    }
    ?>
    <div class="log">
        <a href="delivery.php">Take orders</a>
        <p>Order assigned to you</p>
    </div>
</div>

<div class="table-container">
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>Date/Time</th>
                    <th>Pickup Address</th>
                    <th>Delivery Address</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row) {
                    echo "<tr><td data-label='Name'>".$row['name']."</td>
                          <td data-label='Phone Number'>".$row['phoneno']."</td>
                          <td data-label='Date'>".$row['date']."</td>
                          <td data-label='Pickup Address'>".$row['From_address']."</td>
                          <td data-label='Delivery Address'>".$row['To_address']."</td></tr>";
                } ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>