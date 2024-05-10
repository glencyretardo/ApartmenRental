<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'connection.php';

// Define variables to hold success or error messages
$message = "";
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

    <div class="add-tenant-group">
        <input type="submit" value="Add Tenant" class="button">
     </div>

    <div class="add-tenant-container">
        <div class="box">
            <div class="form-header">
                <h2>Tenant Information</h2>
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">
                    <label for="first_name"> Name:</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="tenant-room">Room:</label>
                    <select id="tenant-room" name="tenant-room" required>
                        <option value="">Select Room</option>
                        <?php
                        // Fetch rooms from the database
                        $fetch_query = "SELECT * FROM room";
                        $result = $conn->query($fetch_query);

                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['room_id'] . "'>" . $row['room_name'] . "</option>";
                            }
                        } else {
                            echo "<option value=''>No rooms found</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="contact-number">Contact Number:</label>
                    <input type="text" id="contact-number" name="contact-number" required>
                </div>
                <div class="form-group">
                    <div class="date-selector">
                        <label for="move-in-date"> Move In date:</label>
                        <input type="date" id="move-in-date" name="move-in-date">
                    </div>
                </div>
                <div class="form-group">
                    <input type="submit" value="Submit" class="button">
                </div>
            </form>
        </div>
    </div>

    <div class="existing-tenant-container">
        <section class="tenant-table">
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
</body>

</html>
