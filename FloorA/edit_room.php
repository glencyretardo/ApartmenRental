<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'connection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Receive and sanitize data
    $room_id = mysqli_real_escape_string($conn, $_POST['edit-room-id']); // This line can be removed since room ID shouldn't be edited
    $room_name = mysqli_real_escape_string($conn, $_POST['edit-room-name']);
    $floor_id = mysqli_real_escape_string($conn, $_POST['edit-floor']);
    $price = mysqli_real_escape_string($conn, $_POST['edit-price']);
    $description = mysqli_real_escape_string($conn, $_POST['edit-description']);

    // Construct the UPDATE query
    $update_query = "UPDATE rooms SET room_name = '$room_name', floor_id = '$floor_id', room_price_per_month = '$price', description = '$description' WHERE room_id = '$room_id'";

    // Execute the UPDATE query
    if ($conn->query($update_query) === TRUE) {
        // Handle success
        $message = "Room updated successfully.";
    } else {
        // Handle failure
        $message = "Error updating room: " . $conn->error;
    }

    // Redirect back to the previous page with the message
    header("Location:s addRooms.php?message=" . urlencode($message));
    exit();
} else {
    // If the request method is not POST, redirect to the previous page
    header("Location: addRooms.php");
    exit();
}
?>
