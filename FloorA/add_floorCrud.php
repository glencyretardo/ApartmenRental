<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
   header("Location: login.php");
   exit();
}

require_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input
    $floor_number = $_POST['floor_number'];

    // Check if the floor number already exists
    $query = "SELECT * FROM floors WHERE floor_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $floor_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $existing_floor = $result->fetch_assoc();

    if ($existing_floor) {
        // Floor number already exists
        echo "Floor number already exists.";
    } else {
        // Insert new floor into the database
        $insert_query = "INSERT INTO floors (floor_number) VALUES (?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param('s', $floor_number);
        
        if ($insert_stmt->execute()) {
            echo "Floor added successfully.";
        } else {
            echo "Error adding floor.";
        }
    }
}
?>
