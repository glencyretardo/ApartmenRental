<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
   header("Location: login.php");
   exit();
}

require_once 'connection.php';

// Define variables to hold success or error messages
$message = "";

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
        $message = "Floor number already exists.";
    } else {
        // Insert new floor into the database
        $insert_query = "INSERT INTO floors (floor_number) VALUES (?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param('s', $floor_number);
        
        if ($insert_stmt->execute()) {
            $message = "Floor added successfully.";
        } else {
            $message = "Error adding floor.";
        }
    }
}
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


    <div class="container">
        <div class="box">
            <div class="form-header">
                <h2>Add Floor</h2>
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">
                    <label for="floor_number">Floor Number:</label>
                    <input type="text" id="floor_number" name="floor_number" required>
                </div>
                <div class="form-group">
                    <input type="submit" value="Add Floor">
                </div>
            </form>
        </div>
    </div>

    <script>
        // Clear form fields when the page is loaded
        window.onload = function() {
            document.getElementById("floor_number").value = "";
        };
    </script>

<script src="hidemessage.js"></script>

</body>

</html>
