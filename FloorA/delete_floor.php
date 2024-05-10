<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['floor_id'])) {
    $floor_id = $_GET['floor_id'];

    // Check if the floor exists
    $check_query = "SELECT * FROM floors WHERE floor_id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param('i', $floor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        // Floor exists, delete it
        $delete_query = "DELETE FROM floors WHERE floor_id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param('i', $floor_id);

        if ($stmt->execute()) {
            // Deletion successful
            header("Location: add_floor.php");
            exit();
        } else {
            // Deletion failed
            echo "Error deleting floor.";
        }
    } else {
        // Floor does not exist
        echo "Floor not found.";
    }
} else {
    // Redirect if accessed without proper parameters
    header("Location: home.php");
    exit();
}
?>
