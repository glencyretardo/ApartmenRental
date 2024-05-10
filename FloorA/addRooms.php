<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'connection.php';

// Define variables to hold success or error messages
$message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_name = $_POST['room_name'];

    // Check if room name already exists
    $check_query = "SELECT * FROM rooms WHERE room_name = '$room_name'";
    $check_result = $conn->query($check_query);

    if ($check_result && $check_result->num_rows > 0) {
        $message = "Room with the same name already exists.";
    } else {
        // If room name doesn't exist, proceed to insert into the database
        $floor_id = $_POST['floor'];
        $price = $_POST['price'];
        $description = $_POST['description'];

        $insert_query = "INSERT INTO rooms (room_name, floor_id, room_price_per_month, description) VALUES ('$room_name', '$floor_id', '$price', '$description')";

        if ($conn->query($insert_query) === TRUE) {
            $message = "Room added successfully.";
        } else {
            $message = "Error: " . $insert_query . "<br>" . $conn->error;
        }
    }
}

// Define variables to hold success or error messages

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

    <!-- Display success or error message -->
    <?php if (!empty($message)) : ?>
        <div class="message">
            <span><?php echo $message; ?></span>

        </div>
    <?php endif; ?>


    <div class="add-rooms-container">
        <div class="box">
            <div class="form-header">
                <h2>Add Room</h2>
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">
                    <label for="room_name">Room Name:</label>
                    <input type="text" id="room_name" name="room_name" required>
                </div>
                <div class="form-group">
                    <label for="floor">Floor:</label>
                    <select id="floor" name="floor" required>

                        <option value="">Select Floor</option>
                        <?php
                        // Fetch floors from the database
                        $fetch_query = "SELECT * FROM floors";
                        $result = $conn->query($fetch_query);

                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['floor_id'] . "'>" . $row['floor_number'] . "</option>";
                            }
                        } else {
                            echo "<option value=''>No rooms found</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="text" id="price" name="price" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <input type="submit" value="Submit" class="button">
                </div>
            </form>
        </div>
    </div>

    <div class="existing-rooms-container">
        <section class="room-table">
            <div class="box">
                <h2>Existing Rooms</h2>
                <table>
                    <tr>
                        <th>Room_ID</th>
                        <th>Room Details</th>
                        <th>Actions</th>
                    </tr>
                    <?php
                    // Fetch existing floors from the database
                    // Fetch existing rooms from the database with associated floor details
                    $fetch_query = "SELECT rooms.room_id, rooms.room_name, rooms.floor_id, rooms.room_price_per_month, rooms.description, floors.floor_number 
FROM rooms 
INNER JOIN floors ON rooms.floor_id = floors.floor_id";
                    $result = $conn->query($fetch_query);

                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['room_id'] . "</td>"; // Assuming 'room_id' is the unique identifier for the room
                            echo "<td>Room Name: " . $row['room_name'] . "<br>";
                            echo "Floor: " . $row['floor_number'] . "<br>";
                            echo "Monthly Rate: ₱" . $row['room_price_per_month'] . "<br>";
                            echo "Description: " . $row['description'] . "</td>";
                            echo "<td>
<button class='button' onclick='editRoom(" . $row['room_id'] . ")'>Edit</button>
<button class='button' onclick='viewRoom(" . $row['room_id'] . ")'>View</button>
<button class='button' onclick='deleteRoom(" . $row['room_id'] . ")'>Delete</button>
</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>No rooms found</td></tr>";
                    }

                    ?>
                </table>
            </div>
        </section>
    </div>


    <!-- Add the hidden edit form section at the bottom of the HTML -->
    <div class="overlay" id="overlay"></div>

    <div class="edit-room-container" style="display: none;">
        <div class="box">
            <div class="form-header">
                <h2>Edit Room</h2>
            </div>
            <form id="edit-room-form" action="edit_room.php" method="POST"> <!-- Replace edit_room.php with the PHP file handling edit operation -->
                <!-- Add hidden input field to store roomId -->
                <input type="hidden" id="edit-room-id" name="edit-room-id">
                <div class="form-group">
                    <label for="edit-room-name">Room Name:</label>
                    <input type="text" id="edit-room-name" name="edit-room-name" required>
                </div>

                <div class="form-group">
                    <label for="floor">Floor:</label>
                    <select id="edit-floor" name="edit-floor" required>

                        <option value="">Select Floor</option>
                        <?php
                        // Fetch floors from the database
                        $fetch_query = "SELECT * FROM floors";
                        $result = $conn->query($fetch_query);

                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['floor_id'] . "'>" . $row['floor_number'] . "</option>";
                            }
                        } else {
                            echo "<option value=''>No rooms found</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="text" id="edit-price" name="edit-price" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="edit-description" name="edit-description" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <input type="submit" value="Update" class="button">
                    <button type="button" class="button" onclick="closeEditForm()">Close</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Update the "Edit" button to call a JavaScript function -->

    <!-- Update the "Edit" button to call a JavaScript function -->
    <script>
        function editRoom(roomId) {
            // Set the room ID in the hidden input field
            document.getElementById('edit-room-id').value = roomId;

            // Make an AJAX request to fetch room details based on the room ID
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4) {
                    if (this.status == 200) {
                        try {
                            var roomDetails = JSON.parse(this.responseText);
                            // Populate the form fields with the fetched room details
                            var editRoomName = document.getElementById('edit-room-name');
                            var editFloor = document.getElementById('edit-floor');
                            var editPrice = document.getElementById('edit-price');
                            var editDescription = document.getElementById('edit-description');

                            if (editRoomName) editRoomName.value = roomDetails.room_name;
                            if (editFloor) editFloor.value = roomDetails.floor_id;
                            if (editPrice) editPrice.value = roomDetails.room_price_per_month;
                            if (editDescription) editDescription.value = roomDetails.description;
                        } catch (error) {
                            console.error("Error parsing JSON:", error);
                        }
                    } else {
                        console.error("Request failed with status:", this.status);
                    }
                }
            };
            xhttp.open("GET", "fetch_room_details.php?roomId=" + roomId, true);
            xhttp.send();

            // Display the overlay and edit-room-container
            var overlay = document.getElementById('overlay');
            var editRoomContainer = document.getElementById('edit-room-container');

            if (overlay) overlay.style.display = 'block';
            if (editRoomContainer) editRoomContainer.style.display = 'block';

        }


        function closeEditForm() {
            // Hide the edit form
            document.querySelector('.edit-room-container').style.display = 'none';
            // Hide the overlay
            document.getElementById('overlay').style.display = 'none';
        }
    </script>

    <script src="hidemessage.js"></script>

</body>

</html>
