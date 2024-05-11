<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection file
    include "connection.php";


    $message = "";
    // Retrieve form data
    $name = $_POST["first_name"];
    $email = $_POST["email"];
    $contact_number = $_POST["contact-number"];
    $move_in_date = $_POST["move-in-date"];
    $room_id = $_POST["tenant-room"];

    // Prepare and execute SQL query to insert data into the tenants table
    $insert_query = "INSERT INTO tenants (name, email, contact_number, move_in_date, room_id) 
                     VALUES ('$name', '$email', '$contact_number', '$move_in_date', $room_id)";

    if ($conn->query($insert_query) === TRUE) {
        $message= "New record created successfully";
        header("Location: addTenant.php");
        exit();
        

    } else {
        $message="Error: " . $insert_query . "<br>" . $conn->error;
    }

    // Close database connection
    $conn->close();
}
?>

 <!-- Display success or error message -->
 <?php if (!empty($message)) : ?>
        <div class="message">
            <span><?php echo $message; ?></span>

        </div>
    <?php endif; ?>

<body>


    <div class="add-tenant-container">
        <div class="add-tenant-container form-container">

            <div class="box">
                <div class="form-header">
                    <h2>Tenant Information</h2>
                </div>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <div class="form-group">
                            <input type="submit" value="Submit" class="button">
                            
                    </div>
                    
                    <div class="form-group">
                        <label for="first_name"> Name:</label>
                        <input type="text" id="first_name" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="text" id="email" name="email" required>
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
                        <label for="tenant-room">Room:</label>
                        <select id="tenant-room" name="tenant-room" required>
                            <option value="">Select Room</option>
                            <?php
                            // Fetch rooms from the database
                            $fetch_query = "SELECT * FROM rooms";
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
                        <button type="button" class="cancel-button" onclick="window.location.href='addTenant.php';">Cancel</button>
                    </div>

                    

                </form>
            </div>
        </div>
    </div>

</body>
