<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
   header("Location: login.php");
   exit();
}
require_once 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <!-- Font Awesome CDN link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <?php include 'header.php'; ?>

    <div class="container">
        <div class="box">
            <div class="form-header">
                <h2>Add Floor</h2>
            </div>
            <form action="add_floor.php" method="POST">
                <div class="form-group">
                    <label for="floor_name">Floor Number:</label>
                    <input type="text" id="floor_name" name="floor_name" required>
                </div>
                <div class="form-group">
                    <input type="submit" value="Add Floor">
                </div>
            </form>
        </div>
    </div>

</body>

</html>
