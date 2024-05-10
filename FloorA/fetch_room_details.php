

<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
require_once 'connection.php';

if(isset($_GET['roomId'])) {
    $roomId = $_GET['roomId'];
    
    $fetch_query = "SELECT * FROM rooms WHERE room_id = '$roomId'";
    $result = $conn->query($fetch_query);

    if ($result && $result->num_rows > 0) {
        $roomDetails = $result->fetch_assoc();
        // Add debugging output
        ob_flush();

        // Encode and return room details as JSON
        echo json_encode($roomDetails);
    } else {
        // Add debugging output
        echo "No room found for roomId: $roomId";
        // Return an empty array if no room found
        echo json_encode(array());
    }
}
?>
