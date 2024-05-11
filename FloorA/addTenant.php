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

    <script>
        function toggleAddTenantForm() {
            var formContainer = document.getElementById('add-tenant-container');
            var overlay = document.querySelector('.overlay');

            if (formContainer.style.display === 'none') {
                formContainer.style.display = 'block';
                overlay.style.display = 'block'; // Show overlay
                document.body.classList.add('blur'); // Add blur effect to the body
            } else {
                formContainer.style.display = 'none';
                overlay.style.display = 'none'; // Hide overlay
                document.body.classList.remove('blur'); // Remove blur effect from the body
            }
        }
    </script>

    <!-- Display success or error message -->
    <?php if (!empty($message)) : ?>
        <div class="message">
            <span><?php echo $message; ?></span>
        </div>
    <?php endif; ?>

    <div class="add-tenant-group">
        <input type="button" value="Add Tenant" class="button" onclick="toggleAddTenantForm()">
    </div>

    <div class="add-tenant-container" id="add-tenant-container" style="display: none;">
        <?php include 'add_tenant_form.php'; ?>
    </div>

   <!-- <div class="overlay"></div> -->


    <div class="existing-tenant-container">
        <section class="tenant-table">
            <div class="box">
                <h2>Existing Rooms</h2>
                <table>
                    <tr>
                        <th>Tenant_ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact Number</th>
                        <th>Move-in Date</th>
                        <th>Room</th>
                        <th>Actions</th>
                    </tr>
                    <?php
                    // Fetch existing rooms from the database with associated floor details
                    $fetch_query = "SELECT tenants.tenant_id, tenants.name, tenants.email, tenants.contact_number, tenants.move_in_date, rooms.room_id, rooms.room_name 
                                    FROM tenants 
                                    INNER JOIN rooms ON tenants.room_id = rooms.room_id";
                    $result = $conn->query($fetch_query);

                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['tenant_id'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['email'] . "</td>";
                            echo "<td>" . $row['contact_number'] . "</td>";
                            echo "<td>" . $row['move_in_date'] . "</td>";
                            echo "<td>" . $row['room_name'] . "</td>";
                            echo "<td>
                                    <button class='button' onclick='editRoom(" . $row['room_id'] . ")'>Edit</button>
                                    <button class='button' onclick='viewRoom(" . $row['room_id'] . ")'>View</button>
                                    <button class='button' onclick='deleteRoom(" . $row['room_id'] . ")'>Delete</button>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No tenants found</td></tr>";
                    }
                    ?>
                </table>
            </div>
        </section>
    </div>

    <script>
        
    </script>

</body>

</html>
